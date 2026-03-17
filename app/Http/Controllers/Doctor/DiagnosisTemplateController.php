<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\DiagnosisTemplate;
use Illuminate\Http\Request;

class DiagnosisTemplateController extends Controller
{
    public function index()
    {
        $doctor = auth()->user()->doctor;
        abort_if(!$doctor, 403);

        $templates = DiagnosisTemplate::where('doctor_id', $doctor->id)
            ->orderBy('usage_count', 'desc')
            ->latest()
            ->get();

        return view('doctor.diagnosis-templates.index', compact('doctor', 'templates'));
    }

    public function store(Request $request)
    {
        $doctor = auth()->user()->doctor;
        $clinic = auth()->user()->clinic;
        abort_if(!$doctor, 403);

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'complaint'   => 'nullable|string|max:2000',
            'diagnosis'   => 'nullable|string|max:2000',
            'prescription'=> 'nullable|string|max:2000',
            'lab_tests'   => 'nullable|string|max:2000',
            'radiology'   => 'nullable|string|max:2000',
            'notes'       => 'nullable|string|max:2000',
            'diagram_data'=> 'nullable|string',
            'rx_drugs'    => 'nullable|string',
        ]);

        DiagnosisTemplate::create([
            'clinic_id'    => $clinic->id,
            'doctor_id'    => $doctor->id,
            'name'         => $validated['name'],
            'complaint'    => $validated['complaint'] ?? null,
            'diagnosis'    => $validated['diagnosis'] ?? null,
            'prescription' => $validated['prescription'] ?? null,
            'lab_tests'    => $validated['lab_tests'] ?? null,
            'radiology'    => $validated['radiology'] ?? null,
            'notes'        => $validated['notes'] ?? null,
            'diagram_data' => isset($validated['diagram_data']) ? json_decode($validated['diagram_data'], true) : null,
            'rx_drugs'     => isset($validated['rx_drugs']) ? json_decode($validated['rx_drugs'], true) : null,
            'usage_count'  => 0,
        ]);

        if (request()->expectsJson()) {
            return response()->json(['message' => __('app.template_saved')]);
        }

        return back()->with('success', __('app.template_saved'));
    }

    public function destroy(DiagnosisTemplate $template)
    {
        $doctor = auth()->user()->doctor;
        abort_if(!$doctor || $template->doctor_id !== $doctor->id, 403);

        $template->delete();

        if (request()->expectsJson()) {
            return response()->json(['message' => __('app.template_deleted')]);
        }

        return back()->with('success', __('app.template_deleted'));
    }

    public function load(DiagnosisTemplate $template)
    {
        $doctor = auth()->user()->doctor;
        abort_if(!$doctor || $template->doctor_id !== $doctor->id, 403);

        $template->increment('usage_count');

        return response()->json([
            'id'           => $template->id,
            'name'         => $template->name,
            'complaint'    => $template->complaint,
            'diagnosis'    => $template->diagnosis,
            'prescription' => $template->prescription,
            'lab_tests'    => $template->lab_tests,
            'radiology'    => $template->radiology,
            'notes'        => $template->notes,
            'diagram_data' => $template->diagram_data,
            'rx_drugs'     => $template->rx_drugs,
        ]);
    }
}
