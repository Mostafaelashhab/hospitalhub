<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Pregnancy;
use App\Models\PregnancyVisit;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PregnancyController extends Controller
{
    public function index(Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        $pregnancies = Pregnancy::where('patient_id', $patient->id)
            ->where('clinic_id', $clinic->id)
            ->with(['doctor', 'visits'])
            ->orderByRaw("FIELD(status, 'active') DESC")
            ->orderByDesc('lmp_date')
            ->get();

        return view('admin.pregnancy.index', compact('patient', 'pregnancies'));
    }

    public function create(Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        $doctors = $clinic->doctors()->where('is_active', true)->get();

        return view('admin.pregnancy.create', compact('patient', 'doctors'));
    }

    public function store(Request $request, Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        $validated = $request->validate([
            'lmp_date'  => 'required|date|before_or_equal:today',
            'doctor_id' => 'nullable|exists:doctors,id',
            'notes'     => 'nullable|string|max:2000',
        ]);

        $lmp = Carbon::parse($validated['lmp_date']);
        $edd = $lmp->copy()->addDays(280);

        Pregnancy::create([
            'patient_id' => $patient->id,
            'clinic_id'  => $clinic->id,
            'doctor_id'  => $validated['doctor_id'] ?? null,
            'lmp_date'   => $lmp->toDateString(),
            'edd_date'   => $edd->toDateString(),
            'notes'      => $validated['notes'] ?? null,
            'status'     => 'active',
        ]);

        return redirect()->route('dashboard.patients.pregnancy.index', $patient)
            ->with('success', __('app.pregnancy_created'));
    }

    public function show(Pregnancy $pregnancy)
    {
        $clinic = auth()->user()->clinic;
        abort_if($pregnancy->clinic_id !== $clinic->id, 403);

        $pregnancy->load(['patient', 'doctor', 'visits' => fn($q) => $q->orderBy('visit_date')]);

        return view('admin.pregnancy.show', compact('pregnancy'));
    }

    public function addVisit(Request $request, Pregnancy $pregnancy)
    {
        $clinic = auth()->user()->clinic;
        abort_if($pregnancy->clinic_id !== $clinic->id, 403);

        $validated = $request->validate([
            'visit_date'               => 'required|date',
            'weight'                   => 'nullable|numeric|min:30|max:200',
            'blood_pressure_systolic'  => 'nullable|integer|min:50|max:250',
            'blood_pressure_diastolic' => 'nullable|integer|min:30|max:150',
            'fundal_height'            => 'nullable|numeric|min:0|max:50',
            'fetal_heart_rate'         => 'nullable|integer|min:50|max:200',
            'presentation'             => 'nullable|string|max:50',
            'notes'                    => 'nullable|string|max:2000',
            'next_visit_date'          => 'nullable|date|after:visit_date',
        ]);

        $visitDate = Carbon::parse($validated['visit_date']);
        $gestationalWeek = (int) min(floor($pregnancy->lmp_date->diffInDays($visitDate) / 7), 42);

        PregnancyVisit::create(array_merge($validated, [
            'pregnancy_id'    => $pregnancy->id,
            'gestational_week' => $gestationalWeek,
        ]));

        return redirect()->route('dashboard.pregnancy.show', $pregnancy)
            ->with('success', __('app.visit_added'));
    }

    public function complete(Request $request, Pregnancy $pregnancy)
    {
        $clinic = auth()->user()->clinic;
        abort_if($pregnancy->clinic_id !== $clinic->id, 403);

        $validated = $request->validate([
            'delivery_date' => 'required|date',
            'delivery_type' => 'required|in:normal,cesarean,assisted',
            'baby_gender'   => 'nullable|in:male,female,unknown',
            'baby_weight'   => 'nullable|numeric|min:0.3|max:8',
            'notes'         => 'nullable|string|max:2000',
        ]);

        $pregnancy->update(array_merge($validated, [
            'status' => 'delivered',
        ]));

        return redirect()->route('dashboard.pregnancy.show', $pregnancy)
            ->with('success', __('app.pregnancy_completed'));
    }

    public function destroy(Pregnancy $pregnancy)
    {
        $clinic = auth()->user()->clinic;
        abort_if($pregnancy->clinic_id !== $clinic->id, 403);

        $patient = $pregnancy->patient;
        $pregnancy->delete();

        return redirect()->route('dashboard.patients.pregnancy.index', $patient)
            ->with('success', __('app.deleted_successfully'));
    }
}
