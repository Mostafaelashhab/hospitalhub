<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ChronicDisease;
use App\Models\MedicalNote;
use App\Models\Patient;
use App\Models\PatientMedication;
use App\Models\VitalSign;
use Illuminate\Http\Request;

class PatientMedicalController extends Controller
{
    // ── Vital Signs ──

    public function storeVitals(Request $request, Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        $validated = $request->validate([
            'blood_pressure_systolic' => 'nullable|numeric|min:50|max:300',
            'blood_pressure_diastolic' => 'nullable|numeric|min:30|max:200',
            'heart_rate' => 'nullable|numeric|min:30|max:250',
            'temperature' => 'nullable|numeric|min:30|max:45',
            'weight' => 'nullable|numeric|min:0.5|max:500',
            'height' => 'nullable|numeric|min:20|max:300',
            'blood_sugar' => 'nullable|numeric|min:20|max:800',
            'oxygen_saturation' => 'nullable|numeric|min:50|max:100',
            'respiratory_rate' => 'nullable|numeric|min:5|max:60',
            'notes' => 'nullable|string|max:500',
        ]);

        VitalSign::create(array_merge($validated, [
            'clinic_id' => $clinic->id,
            'patient_id' => $patient->id,
            'recorded_by' => auth()->id(),
        ]));

        return back()->with('success', __('app.vitals_recorded'));
    }

    // ── Chronic Diseases ──

    public function storeDisease(Request $request, Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        $validated = $request->validate([
            'disease_name' => 'required|string|max:255',
            'diagnosed_date' => 'nullable|date',
            'severity' => 'required|in:mild,moderate,severe',
            'notes' => 'nullable|string|max:500',
        ]);

        ChronicDisease::create(array_merge($validated, [
            'clinic_id' => $clinic->id,
            'patient_id' => $patient->id,
        ]));

        return back()->with('success', __('app.disease_added'));
    }

    public function toggleDisease(ChronicDisease $disease)
    {
        $clinic = auth()->user()->clinic;
        abort_if($disease->clinic_id !== $clinic->id, 403);

        $disease->update(['is_active' => !$disease->is_active]);

        return back()->with('success', __('app.status_updated'));
    }

    public function destroyDisease(ChronicDisease $disease)
    {
        $clinic = auth()->user()->clinic;
        abort_if($disease->clinic_id !== $clinic->id, 403);

        $disease->delete();

        return back()->with('success', __('app.disease_removed'));
    }

    // ── Medications ──

    public function storeMedication(Request $request, Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        $validated = $request->validate([
            'medication_name' => 'required|string|max:255',
            'dosage' => 'nullable|string|max:100',
            'frequency' => 'nullable|string|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'prescribed_by' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        PatientMedication::create(array_merge($validated, [
            'clinic_id' => $clinic->id,
            'patient_id' => $patient->id,
        ]));

        return back()->with('success', __('app.medication_added'));
    }

    public function toggleMedication(PatientMedication $medication)
    {
        $clinic = auth()->user()->clinic;
        abort_if($medication->clinic_id !== $clinic->id, 403);

        $medication->update(['is_active' => !$medication->is_active]);

        return back()->with('success', __('app.status_updated'));
    }

    public function destroyMedication(PatientMedication $medication)
    {
        $clinic = auth()->user()->clinic;
        abort_if($medication->clinic_id !== $clinic->id, 403);

        $medication->delete();

        return back()->with('success', __('app.medication_removed'));
    }

    // ── Medical Notes ──

    public function storeNote(Request $request, Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        $validated = $request->validate([
            'note' => 'required|string|max:5000',
            'type' => 'required|in:general,follow_up,pre_op,post_op,referral',
        ]);

        MedicalNote::create(array_merge($validated, [
            'clinic_id' => $clinic->id,
            'patient_id' => $patient->id,
            'created_by' => auth()->id(),
        ]));

        return back()->with('success', __('app.note_added'));
    }

    public function destroyNote(MedicalNote $note)
    {
        $clinic = auth()->user()->clinic;
        abort_if($note->clinic_id !== $clinic->id, 403);

        $note->delete();

        return back()->with('success', __('app.note_deleted'));
    }
}
