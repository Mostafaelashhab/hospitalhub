<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiagnosisTemplate;
use Illuminate\Http\Request;

class DiagnosisTemplateController extends Controller
{
    public function index(Request $request)
    {
        $clinic = auth()->user()->clinic;

        $query = DiagnosisTemplate::where('clinic_id', $clinic->id)
            ->with('doctor');

        if ($doctorId = $request->get('doctor_id')) {
            $query->where('doctor_id', $doctorId);
        }

        $templates = $query->orderBy('usage_count', 'desc')->latest()->get();
        $doctors = $clinic->doctors()->where('is_active', true)->get();

        return view('admin.diagnosis-templates.index', compact('templates', 'doctors'));
    }

    public function store(Request $request)
    {
        $clinic = auth()->user()->clinic;

        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'doctor_id'   => 'nullable|exists:doctors,id',
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
            'doctor_id'    => $validated['doctor_id'] ?? null,
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

        return back()->with('success', __('app.template_saved'));
    }

    public function destroy(DiagnosisTemplate $template)
    {
        $clinic = auth()->user()->clinic;
        abort_if($template->clinic_id !== $clinic->id, 403);

        $template->delete();

        return back()->with('success', __('app.template_deleted'));
    }

    public function loadTemplate(DiagnosisTemplate $template)
    {
        $clinic = auth()->user()->clinic;
        abort_if($template->clinic_id !== $clinic->id, 403);

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
