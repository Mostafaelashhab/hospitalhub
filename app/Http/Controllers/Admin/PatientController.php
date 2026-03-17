<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\BranchHelper;
use App\Http\Controllers\Controller;
use App\Models\Diagnosis;
use App\Models\Patient;
use App\Models\PlatformSetting;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $clinic = auth()->user()->clinic;

        $branchId = BranchHelper::activeBranchId();

        $query = $clinic->patients();

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('national_id', 'like', "%{$search}%");
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('blood_type')) {
            $query->where('blood_type', $request->blood_type);
        }

        $patients = $query->latest()->paginate(15);

        return view('admin.patients.index', compact('patients'));
    }

    public function create()
    {
        return view('admin.patients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'nullable|date|before:today',
            'address' => 'nullable|string|max:500',
            'medical_history' => 'nullable|string|max:2000',
            'allergies' => 'nullable|string|max:1000',
            'blood_type' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'national_id' => 'nullable|string|max:20',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relation' => 'nullable|string|max:100',
        ]);

        $clinic = auth()->user()->clinic;
        $freeMode = PlatformSetting::isFreeModeActive($clinic);

        // Check wallet balance (skip if free mode)
        $pointCost = (int) PlatformSetting::getPointPrice();
        if (!$freeMode && $pointCost > 0) {
            $wallet = $clinic->wallet;
            if ($wallet && !$wallet->hasEnoughBalance($pointCost)) {
                return back()->withInput()->with('error', __('app.insufficient_points'));
            }
        }

        $patient = $clinic->patients()->create(array_merge($validated, [
            'branch_id' => BranchHelper::activeBranchId(),
        ]));

        // Deduct points (skip if free mode)
        if (!$freeMode && $pointCost > 0) {
            $wallet = $clinic->wallet ?? null;
            if ($wallet) {
                $wallet->debit($pointCost, __('app.point_deducted_patient', ['name' => $patient->name]), 'patient', $patient->id);
            }
        }

        return redirect()->route('dashboard.patients.index')
            ->with('success', __('app.patient_created'));
    }

    public function show(Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        $patient->load([
            'appointments' => function ($q) {
                $q->with('doctor')->latest('appointment_date')->limit(10);
            },
            'files' => function ($q) {
                $q->with('uploader')->latest();
            },
            'insurances' => function ($q) {
                $q->with('provider')->latest();
            },
            'activeInsurance.provider',
            'latestVitals',
            'activeChronicDiseases',
            'activeMedications',
            'vitalSigns' => fn($q) => $q->with('recorder')->latest()->limit(10),
            'medicalNotes' => fn($q) => $q->with('creator')->latest()->limit(20),
        ]);

        $insuranceProviders = $clinic->insuranceProviders()->where('is_active', true)->get();

        return view('admin.patients.show', compact('patient', 'insuranceProviders'));
    }

    public function edit(Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        return view('admin.patients.edit', compact('patient'));
    }

    public function update(Request $request, Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'nullable|date|before:today',
            'address' => 'nullable|string|max:500',
            'medical_history' => 'nullable|string|max:2000',
            'allergies' => 'nullable|string|max:1000',
            'blood_type' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'national_id' => 'nullable|string|max:20',
            'emergency_contact_name' => 'nullable|string|max:255',
            'emergency_contact_phone' => 'nullable|string|max:20',
            'emergency_contact_relation' => 'nullable|string|max:100',
        ]);

        $patient->update($validated);

        return redirect()->route('dashboard.patients.show', $patient)
            ->with('success', __('app.patient_updated'));
    }

    public function timeline(Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        $diagnoses = Diagnosis::where('patient_id', $patient->id)
            ->where('clinic_id', $clinic->id)
            ->with(['doctor', 'appointment'])
            ->latest()
            ->get();

        $appointments = $patient->appointments()
            ->with(['doctor'])
            ->latest('appointment_date')
            ->get();

        $invoices = $patient->invoices()
            ->latest()
            ->get();

        // Build unified timeline
        $timeline = collect();

        foreach ($appointments as $apt) {
            $timeline->push([
                'type' => 'appointment',
                'date' => $apt->appointment_date,
                'time' => $apt->appointment_time,
                'data' => $apt,
            ]);
        }

        foreach ($diagnoses as $diag) {
            $timeline->push([
                'type' => 'diagnosis',
                'date' => $diag->created_at->toDateString(),
                'time' => $diag->created_at->format('H:i'),
                'data' => $diag,
            ]);
        }

        foreach ($invoices as $inv) {
            $timeline->push([
                'type' => 'invoice',
                'date' => $inv->created_at->toDateString(),
                'time' => $inv->created_at->format('H:i'),
                'data' => $inv,
            ]);
        }

        $timeline = $timeline->sortByDesc('date')->values();

        return view('admin.patients.timeline', compact('patient', 'timeline'));
    }

    public function destroy(Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        $patient->delete();

        return redirect()->route('dashboard.patients.index')
            ->with('success', __('app.patient_deleted'));
    }
}
