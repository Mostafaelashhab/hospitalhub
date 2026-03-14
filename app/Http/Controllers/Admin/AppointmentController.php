<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\BranchHelper;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\PatientInsurance;
use App\Models\User;
use App\Notifications\AppointmentCreated;
use App\Notifications\AppointmentStatusChanged;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $clinic = auth()->user()->clinic;

        $branchId = BranchHelper::activeBranchId();

        $query = $clinic->appointments()->with(['patient', 'doctor']);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('date')) {
            $query->whereDate('appointment_date', $request->date);
        }

        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', fn($p) => $p->where('name', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%"))
                  ->orWhereHas('doctor', fn($d) => $d->where('name', 'like', "%{$search}%"));
            });
        }

        $appointments = $query->latest('appointment_date')->latest('appointment_time')->paginate(15);

        $doctorsQuery = $clinic->doctors()->where('is_active', true);
        if ($branchId) {
            $doctorsQuery->where('branch_id', $branchId);
        }
        $doctors = $doctorsQuery->get();

        return view('admin.appointments.index', compact('appointments', 'doctors'));
    }

    public function create()
    {
        $clinic = auth()->user()->clinic;
        $branchId = BranchHelper::activeBranchId();

        $doctorsQuery = $clinic->doctors()->where('is_active', true);
        if ($branchId) {
            $doctorsQuery->where('branch_id', $branchId);
        }
        $doctors = $doctorsQuery->get();

        $patientsQuery = $clinic->patients()->orderBy('name');
        if ($branchId) {
            $patientsQuery->where('branch_id', $branchId);
        }
        $patients = $patientsQuery->get();

        return view('admin.appointments.create', compact('doctors', 'patients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required',
            'notes' => 'nullable|string|max:1000',
            'recurrence_type' => 'nullable|in:none,daily,weekly,biweekly,monthly',
            'recurrence_count' => 'nullable|integer|min:2|max:52',
        ]);

        $clinic = auth()->user()->clinic;
        $branchId = BranchHelper::activeBranchId();
        $recurrenceType = $request->input('recurrence_type', 'none');
        $recurrenceCount = $request->input('recurrence_count', 1);
        $isRecurring = $recurrenceType !== 'none' && $recurrenceCount > 1;
        $groupId = $isRecurring ? Str::uuid()->toString() : null;

        $dates = [$request->appointment_date];

        if ($isRecurring) {
            $baseDate = Carbon::parse($request->appointment_date);
            for ($i = 1; $i < $recurrenceCount; $i++) {
                $nextDate = match ($recurrenceType) {
                    'daily' => $baseDate->copy()->addDays($i),
                    'weekly' => $baseDate->copy()->addWeeks($i),
                    'biweekly' => $baseDate->copy()->addWeeks($i * 2),
                    'monthly' => $baseDate->copy()->addMonths($i),
                    default => $baseDate->copy()->addDays($i),
                };
                $dates[] = $nextDate->format('Y-m-d');
            }
        }

        $firstAppointment = null;
        foreach ($dates as $date) {
            $appointment = Appointment::create([
                'clinic_id' => $clinic->id,
                'branch_id' => $branchId,
                'patient_id' => $request->patient_id,
                'doctor_id' => $request->doctor_id,
                'appointment_date' => $date,
                'appointment_time' => $request->appointment_time,
                'notes' => $request->notes,
                'status' => 'scheduled',
                'recurrence_group_id' => $groupId,
                'recurrence_type' => $recurrenceType,
                'recurrence_count' => $isRecurring ? $recurrenceCount : 1,
            ]);
            $firstAppointment = $firstAppointment ?? $appointment;
        }

        // Notify all clinic staff (doctors, admins, secretaries)
        $staffToNotify = User::where('clinic_id', $clinic->id)
            ->where('id', '!=', request()->user()->id)
            ->whereIn('role', ['admin', 'doctor', 'secretary'])
            ->get();
        foreach ($staffToNotify as $staff) {
            try {
                $staff->notify(new AppointmentCreated($firstAppointment));
            } catch (\Exception $e) {
                // Skip notification failures (e.g. invalid push subscriptions)
            }
        }

        $message = $isRecurring
            ? __('app.recurring_appointments_created', ['count' => count($dates)])
            : __('app.appointment_created');

        return redirect()->route('dashboard.appointments.index')
            ->with('success', $message);
    }

    public function show(Appointment $appointment)
    {
        $clinic = auth()->user()->clinic;
        abort_if($appointment->clinic_id !== $clinic->id, 403);

        $appointment->load(['patient', 'doctor', 'diagnosis', 'invoice']);

        return view('admin.appointments.show', compact('appointment'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $clinic = auth()->user()->clinic;
        abort_if($appointment->clinic_id !== $clinic->id, 403);

        $rules = [
            'status' => 'required|in:scheduled,confirmed,in_progress,completed,cancelled,no_show',
        ];

        if ($request->status === 'completed') {
            $rules['amount'] = 'required|numeric|min:0';
            $rules['create_followup'] = 'nullable|boolean';
            $rules['followup_date'] = 'required_if:create_followup,1|nullable|date|after_or_equal:today';
        }

        $validated = $request->validate($rules);

        $appointment->update(['status' => $validated['status']]);

        // Notify clinic staff about status change
        $clinicStaff = User::where('clinic_id', $clinic->id)
            ->where('id', '!=', request()->user()->id)
            ->whereIn('role', ['admin', 'doctor', 'secretary'])
            ->get();
        foreach ($clinicStaff as $staff) {
            try {
                $staff->notify(new AppointmentStatusChanged($appointment, $validated['status']));
            } catch (\Exception $e) {
                // Skip notification failures
            }
        }

        if ($validated['status'] === 'completed') {
            $amount = $validated['amount'];

            // Calculate insurance coverage
            $insuranceProviderId = null;
            $insuranceCoverage = 0;
            $patientShare = $amount;

            $activeInsurance = PatientInsurance::where('patient_id', $appointment->patient_id)
                ->where('clinic_id', $clinic->id)
                ->where('is_active', true)
                ->with('provider')
                ->first();

            if ($activeInsurance && $activeInsurance->provider && !$activeInsurance->isExpired()) {
                $provider = $activeInsurance->provider;
                $insuranceProviderId = $provider->id;
                $insuranceCoverage = $amount * ($provider->coverage_percentage / 100);
                if ($provider->max_coverage && $insuranceCoverage > $provider->max_coverage) {
                    $insuranceCoverage = $provider->max_coverage;
                }
                $insuranceCoverage = round($insuranceCoverage, 2);
                $patientShare = max(0, $amount - $insuranceCoverage);
            }

            Invoice::create([
                'clinic_id' => $clinic->id,
                'branch_id' => $appointment->branch_id,
                'patient_id' => $appointment->patient_id,
                'appointment_id' => $appointment->id,
                'insurance_provider_id' => $insuranceProviderId,
                'amount' => $amount,
                'discount' => 0,
                'insurance_coverage' => $insuranceCoverage,
                'patient_share' => $patientShare,
                'total' => $patientShare,
                'status' => 'unpaid',
            ]);

            if ($request->boolean('create_followup') && $validated['followup_date']) {
                Appointment::create([
                    'clinic_id' => $clinic->id,
                    'branch_id' => $appointment->branch_id,
                    'patient_id' => $appointment->patient_id,
                    'doctor_id' => $appointment->doctor_id,
                    'appointment_date' => $validated['followup_date'],
                    'appointment_time' => $appointment->appointment_time,
                    'status' => 'scheduled',
                    'notes' => __('app.followup_for', ['id' => $appointment->id]),
                ]);
            }
        }

        return back()->with('success', __('app.status_updated'));
    }
}
