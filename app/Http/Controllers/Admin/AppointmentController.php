<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\BranchHelper;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\DoctorLeave;
use App\Models\DoctorSchedule;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\PatientInsurance;
use App\Models\Service;
use App\Models\User;
use App\Notifications\AppointmentCreated;
use App\Notifications\AppointmentStatusChanged;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $clinic = auth()->user()->clinic;
        $branchId = BranchHelper::activeBranchId();

        // Calendar month
        $month = $request->input('month', now()->month);
        $year = $request->input('year', now()->year);
        $selectedDate = $request->input('date', now()->toDateString());

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        // Get appointments count per day for the calendar
        $monthQuery = $clinic->appointments();
        if ($branchId) {
            $monthQuery->where('branch_id', $branchId);
        }
        if ($request->filled('doctor_id')) {
            $monthQuery->where('doctor_id', $request->doctor_id);
        }
        $appointmentCounts = $monthQuery
            ->whereBetween('appointment_date', [$startOfMonth, $endOfMonth])
            ->selectRaw('appointment_date, count(*) as count')
            ->groupBy('appointment_date')
            ->pluck('count', 'appointment_date')
            ->toArray();

        // Get appointments for the selected day
        $dayQuery = $clinic->appointments()->with(['patient', 'doctor', 'services']);
        if ($branchId) {
            $dayQuery->where('branch_id', $branchId);
        }
        if ($request->filled('doctor_id')) {
            $dayQuery->where('doctor_id', $request->doctor_id);
        }
        if ($request->filled('status')) {
            $dayQuery->where('status', $request->status);
        }
        $dayAppointments = $dayQuery
            ->whereDate('appointment_date', $selectedDate)
            ->orderBy('appointment_time')
            ->get();

        $doctorsQuery = $clinic->doctors()->where('is_active', true);
        if ($branchId) {
            $doctorsQuery->where('branch_id', $branchId);
        }
        $doctors = $doctorsQuery->get();

        return view('admin.appointments.index', compact(
            'dayAppointments', 'doctors', 'appointmentCounts',
            'month', 'year', 'selectedDate', 'startOfMonth', 'endOfMonth'
        ));
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
            'service_ids' => 'nullable|array',
            'service_ids.*' => 'exists:services,id',
            'notes' => 'nullable|string|max:1000',
            'recurrence_type' => 'nullable|in:none,daily,weekly,biweekly,monthly',
            'recurrence_count' => 'nullable|integer|min:2|max:52',
        ]);

        $clinic = auth()->user()->clinic;
        $branchId = BranchHelper::activeBranchId();

        // Check if doctor is on approved leave
        if (DoctorLeave::isOnLeave($request->doctor_id, $request->appointment_date)) {
            return back()->withInput()->withErrors(['appointment_date' => __('app.doctor_on_leave')]);
        }

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

            // Attach services with prices
            if ($request->service_ids) {
                $this->attachServicesWithPrices($appointment, $request->service_ids);
            }

            $firstAppointment ??= $appointment;
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

        // Send WhatsApp notification to patient
        try {
            app(WhatsAppService::class)->notifyAppointmentBooked($firstAppointment);
        } catch (\Exception $e) {
            // Don't block appointment creation if WhatsApp fails
        }

        $message = $isRecurring
            ? __('app.recurring_appointments_created', ['count' => count($dates)])
            : __('app.appointment_created');

        return redirect()->route('dashboard.appointments.index')
            ->with('success', $message);
    }

    public function servicesByDoctor(Doctor $doctor)
    {
        $clinic = auth()->user()->clinic;
        abort_if($doctor->clinic_id !== $clinic->id, 403);

        // Get all services for the doctor's specialty (global + doctor's custom)
        $allServices = Service::where('specialty_id', $doctor->specialty_id)
            ->where('is_active', true)
            ->where(fn($q) => $q->whereNull('doctor_id')->orWhere('doctor_id', $doctor->id))
            ->get();

        // Get doctor's custom prices
        $doctorServices = $doctor->services()
            ->wherePivot('is_active', true)
            ->get()
            ->keyBy('id');

        $services = $allServices->map(function ($s) use ($doctorServices) {
            $doctorService = $doctorServices->get($s->id);
            // Custom service price from services table, or doctor's pivot price
            $price = $s->doctor_id ? (float) $s->price : ($doctorService ? (float) $doctorService->pivot->price : null);
            return [
                'id' => $s->id,
                'name' => app()->getLocale() === 'ar' ? $s->name_ar : $s->name_en,
                'price' => $price,
            ];
        });

        return response()->json($services);
    }

    public function availableSlots(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date',
        ]);

        $clinic = auth()->user()->clinic;
        $doctor = Doctor::where('id', $request->doctor_id)->where('clinic_id', $clinic->id)->firstOrFail();

        $slots = DoctorSchedule::getAvailableSlots($doctor->id, $request->date);

        return response()->json([
            'slots' => $slots,
            'on_leave' => DoctorLeave::isOnLeave($doctor->id, $request->date),
        ]);
    }

    public function show(Appointment $appointment)
    {
        $clinic = auth()->user()->clinic;
        abort_if($appointment->clinic_id !== $clinic->id, 403);

        $appointment->load(['patient', 'doctor', 'diagnosis', 'invoice.items', 'services']);

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
            $rules['amount'] = 'nullable|numeric|min:0';
            $rules['create_followup'] = 'nullable|boolean';
            $rules['followup_date'] = 'required_if:create_followup,1|nullable|date|after_or_equal:today';
        }

        $validated = $request->validate($rules);

        // Enforce state machine — block invalid transitions
        if (!$appointment->canTransitionTo($validated['status'])) {
            return back()->with('error', __('app.invalid_status_transition'));
        }

        $newStatus = $validated['status'];

        // Sync queue_status with appointment status
        $queueUpdate = [];
        if ($newStatus === 'confirmed' && !$appointment->queue_status) {
            $queueUpdate['queue_status'] = 'waiting';
            $queueUpdate['checked_in_at'] = now();
            $queueUpdate['queue_number'] = Appointment::nextQueueNumber($appointment->doctor_id, $appointment->appointment_date);
        } elseif ($newStatus === 'in_progress' && in_array($appointment->queue_status, ['waiting', 'called', null])) {
            $queueUpdate['queue_status'] = 'in_room';
            if (!$appointment->called_at) $queueUpdate['called_at'] = now();
        } elseif ($newStatus === 'completed' && $appointment->queue_status) {
            $queueUpdate['queue_status'] = 'done';
        } elseif (in_array($newStatus, ['cancelled', 'no_show']) && $appointment->queue_status) {
            $queueUpdate['queue_status'] = 'skipped';
        }

        $appointment->update(array_merge(['status' => $newStatus], $queueUpdate));

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
            $appointment->load('services');

            // Calculate amount from services or fallback to manual amount/consultation fee
            $servicesTotal = $appointment->servicesTotal();
            $amount = $servicesTotal > 0 ? $servicesTotal : ($validated['amount'] ?? $appointment->doctor->consultation_fee ?? 0);

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

            $invoice = Invoice::create([
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

            // Create invoice items from appointment services
            if ($appointment->services->isNotEmpty()) {
                foreach ($appointment->services as $service) {
                    $invoice->items()->create([
                        'service_id' => $service->id,
                        'description' => app()->getLocale() === 'ar' ? $service->name_ar : $service->name_en,
                        'price' => $service->pivot->price,
                        'quantity' => 1,
                        'total' => $service->pivot->price,
                    ]);
                }
            } else {
                // Fallback: single consultation fee item
                $invoice->items()->create([
                    'description' => __('app.consultation_fee'),
                    'price' => $amount,
                    'quantity' => 1,
                    'total' => $amount,
                ]);
            }

            if ($request->boolean('create_followup') && $validated['followup_date']) {
                $followUp = Appointment::create([
                    'clinic_id' => $clinic->id,
                    'branch_id' => $appointment->branch_id,
                    'patient_id' => $appointment->patient_id,
                    'doctor_id' => $appointment->doctor_id,
                    'appointment_date' => $validated['followup_date'],
                    'appointment_time' => $appointment->appointment_time,
                    'status' => 'scheduled',
                    'notes' => __('app.followup_for', ['id' => $appointment->id]),
                ]);

                // Send WhatsApp follow-up notification
                try {
                    app(WhatsAppService::class)->notifyFollowUpCreated($followUp, $appointment);
                } catch (\Exception $e) {
                    // Don't block if WhatsApp fails
                }
            }
        }

        return back()->with('success', __('app.status_updated'));
    }

    private function attachServicesWithPrices(Appointment $appointment, array $serviceIds): void
    {
        $doctor = $appointment->doctor;
        $doctorServices = $doctor->services()->wherePivot('is_active', true)->get()->keyBy('id');

        $syncData = [];
        foreach ($serviceIds as $serviceId) {
            $service = Service::find($serviceId);
            if (!$service) continue;

            // Price priority: doctor_service pivot > services.price > 0
            $doctorService = $doctorServices->get($serviceId);
            $price = $doctorService ? (float) $doctorService->pivot->price : ($service->price ?? 0);

            $syncData[$serviceId] = ['price' => $price];
        }

        $appointment->services()->attach($syncData);
    }
}
