<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DentalChart;
use App\Models\Patient;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class DentalChartController extends Controller
{
    /**
     * Show the latest dental chart for a patient (or an empty one).
     */
    public function show(Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        $chart = DentalChart::where('patient_id', $patient->id)
            ->where('clinic_id', $clinic->id)
            ->latest()
            ->first();

        // Build a complete tooth map merging saved data with defaults
        $allTeeth = DentalChart::allToothNumbers();
        $savedData = $chart ? ($chart->tooth_data ?? []) : [];

        $toothData = [];
        foreach ($allTeeth as $tooth) {
            $toothData[$tooth] = array_merge(
                ['status' => 'healthy', 'notes' => ''],
                $savedData[$tooth] ?? []
            );
        }

        $statuses = DentalChart::statuses();

        return view('admin.dental-chart.index', compact('patient', 'chart', 'toothData', 'statuses'));
    }

    /**
     * Save / update a dental chart for a patient.
     */
    public function store(Request $request, Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        $request->validate([
            'tooth_data'     => 'nullable|string',
            'notes'          => 'nullable|string|max:5000',
            'appointment_id' => 'nullable|exists:appointments,id',
        ]);

        // Decode and sanitize tooth_data
        $rawTeethJson = $request->input('tooth_data');
        $decoded      = $rawTeethJson ? json_decode($rawTeethJson, true) : [];
        $allTeeth     = DentalChart::allToothNumbers();
        $validStatuses = DentalChart::statuses();

        $sanitized = [];
        foreach ($allTeeth as $tooth) {
            $entry  = $decoded[$tooth] ?? [];
            $status = $entry['status'] ?? 'healthy';
            if (!in_array($status, $validStatuses)) {
                $status = 'healthy';
            }
            $sanitized[$tooth] = [
                'status' => $status,
                'notes'  => substr((string)($entry['notes'] ?? ''), 0, 500),
            ];
        }

        // Determine the doctor: the logged-in user if they are a doctor
        $doctorId = null;
        $user     = auth()->user();
        if ($user->doctor) {
            $doctorId = $user->doctor->id;
        }

        DentalChart::create([
            'patient_id'     => $patient->id,
            'clinic_id'      => $clinic->id,
            'doctor_id'      => $doctorId,
            'appointment_id' => $request->input('appointment_id'),
            'tooth_data'     => $sanitized,
            'notes'          => $request->input('notes'),
        ]);

        $route = auth()->user()->role === 'doctor' && request()->is('doctor/*')
            ? 'doctor.dental-chart.show'
            : 'dashboard.patients.dental-chart.show';

        return redirect()
            ->route($route, $patient)
            ->with('success', __('app.chart_saved'));
    }

    /**
     * Export the latest dental chart as PDF.
     */
    public function exportPdf(Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        $chart = DentalChart::where('patient_id', $patient->id)
            ->where('clinic_id', $clinic->id)
            ->with('doctor')
            ->latest()
            ->first();

        $allTeeth = DentalChart::allToothNumbers();
        $savedData = $chart ? ($chart->tooth_data ?? []) : [];

        $toothData = [];
        foreach ($allTeeth as $tooth) {
            $toothData[$tooth] = array_merge(
                ['status' => 'healthy', 'notes' => ''],
                $savedData[$tooth] ?? []
            );
        }

        $pdf = Pdf::loadView('admin.dental-chart.pdf', [
            'patient' => $patient,
            'clinic' => $clinic,
            'chart' => $chart,
            'toothData' => $toothData,
        ])->setPaper('a4', 'landscape');

        $filename = 'dental-chart-' . $patient->name . '-' . now()->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * List all dental chart records for a patient.
     */
    public function history(Patient $patient)
    {
        $clinic = auth()->user()->clinic;
        abort_if($patient->clinic_id !== $clinic->id, 403);

        $charts = DentalChart::where('patient_id', $patient->id)
            ->where('clinic_id', $clinic->id)
            ->with('doctor')
            ->latest()
            ->paginate(20);

        return view('admin.dental-chart.history', compact('patient', 'charts'));
    }
}
