<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Diagnosis;
use App\Models\Patient;
use App\Models\User;
use App\Notifications\AppointmentStatusChanged;
use App\Notifications\DiagnosisRecorded;
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

        return view('doctor.dashboard', compact('doctor', 'stats', 'todayAppointments', 'upcomingAppointments'));
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

        $appointment->load(['patient', 'diagnosis']);

        $clinic = $request->user()->clinic;
        $specialty = $clinic->specialty ?? $doctor->specialty;
        $diagramType = $this->getDiagramType($specialty?->name_en);

        return view('doctor.appointment-show', compact('doctor', 'appointment', 'diagramType'));
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
        ]);

        $data = [
            'clinic_id' => $clinic->id,
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

        Diagnosis::updateOrCreate(
            ['appointment_id' => $appointment->id],
            $data
        );

        // Notify clinic admins about diagnosis
        $admins = User::where('clinic_id', $clinic->id)
            ->where('role', 'admin')
            ->get();
        foreach ($admins as $admin) {
            $admin->notify(new DiagnosisRecorded($appointment));
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

        $appointment->update(['status' => $validated['status']]);

        // Notify clinic admins
        $clinic = $request->user()->clinic;
        $admins = User::where('clinic_id', $clinic->id)
            ->where('role', 'admin')
            ->get();
        foreach ($admins as $admin) {
            $admin->notify(new AppointmentStatusChanged($appointment, $validated['status']));
        }

        return back()->with('success', __('app.status_updated'));
    }

    public function settings(Request $request)
    {
        $doctor = $request->user()->doctor;
        abort_if(!$doctor, 403);

        return view('doctor.settings', compact('doctor'));
    }

    public function updateSettings(Request $request)
    {
        $doctor = $request->user()->doctor;
        abort_if(!$doctor, 403);

        $validated = $request->validate([
            'consultation_fee' => 'required|numeric|min:0',
            'working_days' => 'nullable|array',
            'working_days.*' => 'in:saturday,sunday,monday,tuesday,wednesday,thursday,friday',
            'working_from' => 'nullable|date_format:H:i',
            'working_to' => 'nullable|date_format:H:i',
        ]);

        $doctor->update([
            'consultation_fee' => $validated['consultation_fee'],
            'working_days' => $validated['working_days'] ?? [],
            'working_from' => $validated['working_from'],
            'working_to' => $validated['working_to'],
        ]);

        return back()->with('success', __('app.settings_saved'));
    }

    private function getDiagramType(?string $specialtyName): string
    {
        $map = [
            'Dentistry' => 'dental', 'Ophthalmology' => 'ophthalmology', 'Dermatology' => 'dermatology',
            'Cardiology' => 'cardiology', 'Orthopedics' => 'orthopedics', 'Pediatrics' => 'pediatrics',
            'ENT' => 'ent', 'Gynecology' => 'gynecology', 'Urology' => 'urology',
            'Neurology' => 'neurology', 'General Medicine' => 'general', 'Psychiatry' => 'psychiatry',
            'Internal Medicine' => 'internal-medicine', 'Physiotherapy' => 'physiotherapy', 'Nutrition' => 'nutrition',
        ];
        return $map[$specialtyName] ?? 'general';
    }
}
