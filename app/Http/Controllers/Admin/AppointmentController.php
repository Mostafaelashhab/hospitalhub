<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\BranchHelper;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Invoice;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;

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
        ]);

        $clinic = auth()->user()->clinic;

        Appointment::create([
            'clinic_id' => $clinic->id,
            'branch_id' => BranchHelper::activeBranchId(),
            'patient_id' => $request->patient_id,
            'doctor_id' => $request->doctor_id,
            'appointment_date' => $request->appointment_date,
            'appointment_time' => $request->appointment_time,
            'notes' => $request->notes,
            'status' => 'scheduled',
        ]);

        return redirect()->route('dashboard.appointments.index')
            ->with('success', __('app.appointment_created'));
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

        if ($validated['status'] === 'completed') {
            $amount = $validated['amount'];

            Invoice::create([
                'clinic_id' => $clinic->id,
                'branch_id' => $appointment->branch_id,
                'patient_id' => $appointment->patient_id,
                'appointment_id' => $appointment->id,
                'amount' => $amount,
                'discount' => 0,
                'total' => $amount,
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
