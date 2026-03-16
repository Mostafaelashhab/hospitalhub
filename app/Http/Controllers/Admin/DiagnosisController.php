<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\BranchHelper;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Diagnosis;
use App\Models\Prescription;
use Illuminate\Http\Request;

class DiagnosisController extends Controller
{
    public function index(Request $request)
    {
        $clinic = auth()->user()->clinic;

        $branchId = BranchHelper::activeBranchId();

        $query = Diagnosis::where('clinic_id', $clinic->id)
            ->with(['patient', 'doctor', 'appointment']);

        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', fn($p) => $p->where('name', 'like', "%{$search}%")->orWhere('phone', 'like', "%{$search}%"))
                  ->orWhereHas('doctor', fn($d) => $d->where('name', 'like', "%{$search}%"))
                  ->orWhere('complaint', 'like', "%{$search}%")
                  ->orWhere('diagnosis', 'like', "%{$search}%");
            });
        }

        if ($doctorId = $request->get('doctor_id')) {
            $query->where('doctor_id', $doctorId);
        }

        $diagnoses = $query->latest()->paginate(15);
        $doctors = $clinic->doctors()->where('is_active', true)->get();

        return view('admin.diagnoses.index', compact('diagnoses', 'doctors'));
    }

    public function create(Appointment $appointment)
    {
        $clinic = auth()->user()->clinic;
        abort_if($appointment->clinic_id !== $clinic->id, 403);

        $appointment->load(['patient', 'doctor.specialty']);

        $specialty = $appointment->doctor->specialty ?? $clinic->specialty;
        $diagramType = $this->getDiagramType($specialty?->name_en);

        $diagnosis = $appointment->diagnosis;

        return view('admin.diagnoses.create', compact('appointment', 'diagramType', 'diagnosis'));
    }

    public function store(Request $request, Appointment $appointment)
    {
        $clinic = auth()->user()->clinic;
        abort_if($appointment->clinic_id !== $clinic->id, 403);

        $validated = $request->validate([
            'complaint' => 'nullable|string|max:2000',
            'diagnosis' => 'nullable|string|max:2000',
            'prescription' => 'nullable|string|max:2000',
            'lab_tests' => 'nullable|string|max:2000',
            'radiology' => 'nullable|string|max:2000',
            'notes' => 'nullable|string|max:2000',
            'diagram_data' => 'nullable|json',
            'rx_drugs_json' => 'nullable|string',
            'rx_drugs' => 'nullable|array',
            'rx_drugs.*.drug_name' => 'required|string|max:255',
            'rx_drugs.*.dosage' => 'nullable|string|max:255',
            'rx_drugs.*.frequency' => 'nullable|string|max:255',
            'rx_drugs.*.duration' => 'nullable|string|max:255',
            'rx_drugs.*.instructions' => 'nullable|string|max:500',
            'rx_notes' => 'nullable|string|max:1000',
        ]);

        // Parse rx_drugs from JSON (doctor portal) or array (admin portal)
        $rxDrugs = $validated['rx_drugs'] ?? [];
        if (empty($rxDrugs) && !empty($validated['rx_drugs_json'])) {
            $rxDrugs = json_decode($validated['rx_drugs_json'], true) ?? [];
        }

        $data = [
            'clinic_id' => $clinic->id,
            'branch_id' => $appointment->branch_id,
            'appointment_id' => $appointment->id,
            'patient_id' => $appointment->patient_id,
            'doctor_id' => $appointment->doctor_id,
            'complaint' => $validated['complaint'],
            'diagnosis' => $validated['diagnosis'],
            'prescription' => $validated['prescription'],
            'lab_tests' => $validated['lab_tests'],
            'radiology' => $validated['radiology'],
            'notes' => $validated['notes'],
            'diagram_data' => $validated['diagram_data'] ? json_decode($validated['diagram_data'], true) : null,
        ];

        $diagnosisRecord = Diagnosis::updateOrCreate(
            ['appointment_id' => $appointment->id],
            $data
        );

        // Save structured prescription
        if (!empty($rxDrugs)) {
            $prescription = Prescription::updateOrCreate(
                ['diagnosis_id' => $diagnosisRecord->id],
                [
                    'clinic_id' => $clinic->id,
                    'branch_id' => $appointment->branch_id,
                    'patient_id' => $appointment->patient_id,
                    'doctor_id' => $appointment->doctor_id,
                    'notes' => $validated['rx_notes'] ?? null,
                ]
            );

            $prescription->items()->delete();
            foreach ($rxDrugs as $drug) {
                $prescription->items()->create([
                    'drug_name' => $drug['drug_name'] ?? '',
                    'dosage' => $drug['dosage'] ?? null,
                    'frequency' => $drug['frequency'] ?? null,
                    'duration' => $drug['duration'] ?? null,
                    'instructions' => $drug['instructions'] ?? null,
                ]);
            }
        }

        return redirect()->route('dashboard.appointments.show', $appointment)
            ->with('success', __('app.diagnosis_saved'));
    }

    public function show(Diagnosis $diagnosis)
    {
        $clinic = auth()->user()->clinic;
        abort_if($diagnosis->clinic_id !== $clinic->id, 403);

        $diagnosis->load(['appointment', 'patient', 'doctor.specialty', 'prescription.items']);

        $specialty = $diagnosis->doctor->specialty ?? $clinic->specialty;
        $diagramType = $this->getDiagramType($specialty?->name_en);

        return view('admin.diagnoses.show', compact('diagnosis', 'diagramType'));
    }

    private function getDiagramType(?string $specialtyName): string
    {
        $map = [
            'Dentistry' => 'dental',
            'Ophthalmology' => 'ophthalmology',
            'Dermatology' => 'dermatology',
            'Cardiology' => 'cardiology',
            'Orthopedics' => 'orthopedics',
            'Pediatrics' => 'pediatrics',
            'ENT' => 'ent',
            'Gynecology' => 'gynecology',
            'Urology' => 'urology',
            'Neurology' => 'neurology',
            'General Medicine' => 'general',
            'Psychiatry' => 'psychiatry',
            'Internal Medicine' => 'internal-medicine',
            'Physiotherapy' => 'physiotherapy',
            'Nutrition' => 'nutrition',
            'Plastic Surgery' => 'plastic-surgery',
            'Cosmetic Dermatology' => 'cosmetic-dermatology',
        ];

        return $map[$specialtyName] ?? 'general';
    }
}
