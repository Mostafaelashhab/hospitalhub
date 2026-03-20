<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Diagnosis;
use App\Models\Patient;
use App\Models\Prescription;
use App\Models\PrescriptionItem;
use App\Models\User;
use App\Notifications\AppointmentStatusChanged;
use App\Notifications\DiagnosisRecorded;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;

class DoctorDashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $doctor = $user->doctor;

        if (!$doctor) {
            abort(403, 'No doctor profile linked.');
        }

        $stats = [
            'today_appointments' => $doctor->appointments()->where('appointment_date', today())->count(),
            'week_appointments' => $doctor->appointments()->whereBetween('appointment_date', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'total_patients' => $doctor->appointments()->distinct('patient_id')->count('patient_id'),
            'total_diagnoses' => $doctor->diagnoses()->count(),
            'completed_today' => $doctor->appointments()->where('appointment_date', today())->where('status', 'completed')->count(),
            'pending_today' => $doctor->appointments()->where('appointment_date', today())->whereIn('status', ['scheduled', 'confirmed'])->count(),
        ];

        $todayAppointments = $doctor->appointments()
            ->with(['patient'])
            ->where('appointment_date', today())
            ->orderBy('appointment_time')
            ->get();

        $upcomingAppointments = $doctor->appointments()
            ->with(['patient'])
            ->where('appointment_date', '>', today())
            ->orderBy('appointment_date')
            ->orderBy('appointment_time')
            ->take(5)
            ->get();

        $queueWaiting = $doctor->appointments()
            ->with(['patient'])
            ->where('appointment_date', today())
            ->where('queue_status', 'waiting')
            ->orderBy('queue_number')
            ->get();

        $queueCurrent = $doctor->appointments()
            ->with(['patient'])
            ->where('appointment_date', today())
            ->where('queue_status', 'in_room')
            ->first();

        return view('doctor.dashboard', compact('doctor', 'stats', 'todayAppointments', 'upcomingAppointments', 'queueWaiting', 'queueCurrent'));
    }

    public function appointments(Request $request)
    {
        $doctor = $request->user()->doctor;

        $query = $doctor->appointments()->with(['patient']);

        if ($date = $request->get('date')) {
            $query->where('appointment_date', $date);
        } else {
            $query->where('appointment_date', '>=', today());
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $appointments = $query->orderBy('appointment_date')->orderBy('appointment_time')->paginate(20);

        return view('doctor.appointments', compact('doctor', 'appointments'));
    }

    public function showAppointment(Request $request, Appointment $appointment)
    {
        $doctor = $request->user()->doctor;
        abort_if($appointment->doctor_id !== $doctor->id, 403);

        $appointment->load(['patient', 'diagnosis.prescription.items', 'services']);

        $specialty = $doctor->specialty ?? $request->user()->clinic?->specialty;
        $diagramType = $this->getDiagramType($specialty?->name_en);

        // Get available services for this doctor
        $doctorServices = $doctor->services()->wherePivot('is_active', true)->get()->keyBy('id');
        $allServices = \App\Models\Service::where('specialty_id', $doctor->specialty_id)
            ->where('is_active', true)
            ->where(fn($q) => $q->whereNull('doctor_id')->orWhere('doctor_id', $doctor->id))
            ->get()
            ->map(function ($s) use ($doctorServices) {
                $ds = $doctorServices->get($s->id);
                return [
                    'id' => $s->id,
                    'name' => app()->getLocale() === 'ar' ? $s->name_ar : $s->name_en,
                    'price' => $s->doctor_id ? (float) $s->price : ($ds ? (float) $ds->pivot->price : null),
                ];
            });

        $availableServices = $allServices;

        return view('doctor.appointment-show', compact('doctor', 'appointment', 'diagramType', 'availableServices'));
    }

    public function patientHistory(Request $request, Patient $patient)
    {
        $doctor = $request->user()->doctor;
        $clinic = $request->user()->clinic;

        abort_if($patient->clinic_id !== $clinic->id, 403);

        $appointments = Appointment::where('doctor_id', $doctor->id)
            ->where('patient_id', $patient->id)
            ->with(['diagnosis'])
            ->latest('appointment_date')
            ->get();

        $allDiagnoses = Diagnosis::where('patient_id', $patient->id)
            ->where('clinic_id', $clinic->id)
            ->with(['doctor'])
            ->latest()
            ->get();

        return view('doctor.patient-history', compact('doctor', 'patient', 'appointments', 'allDiagnoses'));
    }

    public function storeDiagnosis(Request $request, Appointment $appointment)
    {
        $doctor = $request->user()->doctor;
        $clinic = $request->user()->clinic;
        abort_if($appointment->doctor_id !== $doctor->id, 403);

        $validated = $request->validate([
            'complaint' => 'nullable|string|max:2000',
            'diagnosis' => 'nullable|string|max:2000',
            'prescription' => 'nullable|string|max:2000',
            'lab_tests' => 'nullable|string|max:2000',
            'radiology' => 'nullable|string|max:2000',
            'notes' => 'nullable|string|max:2000',
            'diagram_data' => 'nullable|json',
            'rx_drugs_json' => 'nullable|string',
            'rx_notes' => 'nullable|string|max:1000',
            'service_ids' => 'nullable|array',
            'service_ids.*' => 'exists:services,id',
        ]);

        // Parse rx_drugs from JSON
        $rxDrugs = [];
        if (!empty($validated['rx_drugs_json'])) {
            $rxDrugs = json_decode($validated['rx_drugs_json'], true) ?? [];
        }

        $data = [
            'clinic_id' => $clinic->id,
            'branch_id' => $appointment->branch_id,
            'appointment_id' => $appointment->id,
            'patient_id' => $appointment->patient_id,
            'doctor_id' => $doctor->id,
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
                    'doctor_id' => $doctor->id,
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

        // Sync services to appointment
        if (isset($validated['service_ids'])) {
            $doctorServices = $doctor->services()->wherePivot('is_active', true)->get()->keyBy('id');
            $syncData = [];
            foreach ($validated['service_ids'] as $serviceId) {
                $service = \App\Models\Service::find($serviceId);
                if (!$service) continue;
                $ds = $doctorServices->get($serviceId);
                $price = $service->doctor_id ? (float) $service->price : ($ds ? (float) $ds->pivot->price : 0);
                $syncData[$serviceId] = ['price' => $price];
            }
            $appointment->services()->sync($syncData);
        }

        // Notify clinic admins about diagnosis
        $admins = User::where('clinic_id', $clinic->id)
            ->where('role', 'admin')
            ->get();
        foreach ($admins as $admin) {
            try {
                $admin->notify(new DiagnosisRecorded($appointment));
            } catch (\Exception $e) {
                // Skip notification failures
            }
        }

        // Send WhatsApp notification with diagnosis details
        try {
            app(WhatsAppService::class)->notifyDiagnosisCreated($diagnosisRecord);
        } catch (\Exception $e) {
            // Don't block if WhatsApp fails
        }

        return redirect()->route('doctor.appointment.show', $appointment)
            ->with('success', __('app.diagnosis_saved'));
    }

    public function updateStatus(Request $request, Appointment $appointment)
    {
        $doctor = $request->user()->doctor;
        abort_if($appointment->doctor_id !== $doctor->id, 403);

        $validated = $request->validate([
            'status' => 'required|in:in_progress,completed',
        ]);

        // Enforce state machine
        if (!$appointment->canTransitionTo($validated['status'])) {
            return back()->with('error', __('app.invalid_status_transition'));
        }

        $updateData = ['status' => $validated['status']];
        if ($validated['status'] === 'completed' && $appointment->queue_status) {
            $updateData['queue_status'] = 'done';
        }
        if ($validated['status'] === 'in_progress' && in_array($appointment->queue_status, ['waiting', 'called', null])) {
            $updateData['queue_status'] = 'in_room';
            if (!$appointment->called_at) $updateData['called_at'] = now();
        }
        $appointment->update($updateData);

        // Notify clinic admins
        $clinic = $request->user()->clinic;
        $admins = User::where('clinic_id', $clinic->id)
            ->where('role', 'admin')
            ->get();
        foreach ($admins as $admin) {
            try {
                $admin->notify(new AppointmentStatusChanged($appointment, $validated['status']));
            } catch (\Exception $e) {
                // Skip notification failures
            }
        }

        return back()->with('success', __('app.status_updated'));
    }

    public function queue(Request $request)
    {
        $doctor = $request->user()->doctor;
        abort_if(!$doctor, 403);

        $todayQueue = $doctor->appointments()
            ->with(['patient'])
            ->where('appointment_date', today())
            ->whereNotNull('queue_status')
            ->whereIn('queue_status', ['waiting', 'called', 'in_room'])
            ->orderBy('queue_number')
            ->get();

        $currentPatient = $todayQueue->firstWhere('queue_status', 'in_room');
        $calledPatient = $todayQueue->firstWhere('queue_status', 'called');
        $waitingPatients = $todayQueue->where('queue_status', 'waiting');

        return view('doctor.queue', compact('doctor', 'todayQueue', 'currentPatient', 'calledPatient', 'waitingPatients'));
    }

    public function callPatient(Request $request, Appointment $appointment)
    {
        $doctor = $request->user()->doctor;
        abort_if($appointment->doctor_id !== $doctor->id, 403);

        if ($appointment->queue_status !== 'waiting') {
            return back()->with('error', __('app.patient_not_waiting'));
        }

        $appointment->update([
            'queue_status' => 'called',
            'called_at' => now(),
        ]);

        return back()->with('success', __('app.patient_called'));
    }

    public function startFromQueue(Request $request, Appointment $appointment)
    {
        $doctor = $request->user()->doctor;
        abort_if($appointment->doctor_id !== $doctor->id, 403);

        if (!in_array($appointment->queue_status, ['waiting', 'called'])) {
            return back()->with('error', __('app.invalid_queue_action'));
        }

        // Mark any current in_room appointment as done in queue
        $doctor->appointments()
            ->where('appointment_date', today())
            ->where('queue_status', 'in_room')
            ->update(['queue_status' => 'done']);

        $appointment->update([
            'queue_status' => 'in_room',
            'status' => 'in_progress',
            'called_at' => $appointment->called_at ?? now(),
        ]);

        return redirect()->route('doctor.appointment.show', $appointment)
            ->with('success', __('app.patient_in_room'));
    }

    public function settings(Request $request)
    {
        $doctor = $request->user()->doctor;
        abort_if(!$doctor, 403);

        $doctor->load('services');
        $specialtyServices = \App\Models\Service::where('specialty_id', $doctor->specialty_id)
            ->where('is_active', true)
            ->whereNull('doctor_id')
            ->get();
        $customServices = \App\Models\Service::where('doctor_id', $doctor->id)
            ->where('is_active', true)
            ->get();

        return view('doctor.settings', compact('doctor', 'specialtyServices', 'customServices'));
    }

    public function updateSettings(Request $request)
    {
        $doctor = $request->user()->doctor;
        abort_if(!$doctor, 403);

        $validated = $request->validate([
            'consultation_fee' => 'required|numeric|min:0',
            'working_days' => 'nullable|array',
            'working_days.*' => 'in:sat,sun,mon,tue,wed,thu,fri',
            'working_from' => 'nullable|date_format:H:i',
            'working_to' => 'nullable|date_format:H:i',
            'services' => 'nullable|array',
            'services.*.price' => 'nullable|numeric|min:0',
            'services.*.is_active' => 'nullable|boolean',
            'custom_service_name_ar' => 'nullable|string|max:255',
            'custom_service_name_en' => 'nullable|string|max:255',
            'custom_service_price' => 'nullable|numeric|min:0',
        ]);

        $doctor->update([
            'consultation_fee' => $validated['consultation_fee'],
            'working_days' => $validated['working_days'] ?? [],
            'working_from' => $validated['working_from'],
            'working_to' => $validated['working_to'],
        ]);

        // Sync service prices
        if (!empty($validated['services'])) {
            $syncData = [];
            foreach ($validated['services'] as $serviceId => $data) {
                $price = $data['price'] ?? null;
                if ($price !== null && $price !== '') {
                    $syncData[$serviceId] = [
                        'price' => $price,
                        'is_active' => $data['is_active'] ?? false,
                    ];
                }
            }
            $doctor->services()->sync($syncData);
        }

        // Create custom service
        if (!empty($validated['custom_service_name_ar']) || !empty($validated['custom_service_name_en'])) {
            \App\Models\Service::create([
                'specialty_id' => $doctor->specialty_id,
                'doctor_id' => $doctor->id,
                'name_ar' => $validated['custom_service_name_ar'] ?? $validated['custom_service_name_en'],
                'name_en' => $validated['custom_service_name_en'] ?? $validated['custom_service_name_ar'],
                'price' => $validated['custom_service_price'] ?? 0,
                'is_active' => true,
            ]);
        }

        return back()->with('success', __('app.settings_saved'));
    }

    public function destroyService(\App\Models\Service $service)
    {
        $doctor = request()->user()->doctor;
        abort_if(!$doctor || $service->doctor_id !== $doctor->id, 403);

        $service->delete();

        return back()->with('success', __('app.service_deleted'));
    }

    private function getDiagramType(?string $specialtyName): string
    {
        $map = [
            'Dentistry' => 'dental', 'Ophthalmology' => 'ophthalmology', 'Dermatology' => 'dermatology',
            'Cardiology' => 'cardiology', 'Orthopedics' => 'orthopedics', 'Pediatrics' => 'pediatrics',
            'ENT' => 'ent', 'Gynecology' => 'gynecology', 'Urology' => 'urology',
            'Neurology' => 'neurology', 'General Medicine' => 'general', 'Psychiatry' => 'psychiatry',
            'Internal Medicine' => 'internal-medicine', 'Physiotherapy' => 'physiotherapy', 'Nutrition' => 'nutrition',
            'Plastic Surgery' => 'plastic-surgery', 'Cosmetic Dermatology' => 'cosmetic-dermatology',
            'Colorectal Surgery' => 'colorectal',
        ];
        return $map[$specialtyName] ?? 'general';
    }
}
