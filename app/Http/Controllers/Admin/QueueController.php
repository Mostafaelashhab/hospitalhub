<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\BranchHelper;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use Illuminate\Http\Request;

class QueueController extends Controller
{
    public function index(Request $request)
    {
        $clinic = auth()->user()->clinic;
        $branchId = BranchHelper::activeBranchId();
        $date = $request->input('date', now()->format('Y-m-d'));

        $doctorsQuery = $clinic->doctors()->where('is_active', true);
        if ($branchId) {
            $doctorsQuery->where('branch_id', $branchId);
        }
        $doctors = $doctorsQuery->get();

        $selectedDoctorId = $request->input('doctor_id');

        $appointmentsQuery = Appointment::where('clinic_id', $clinic->id)
            ->whereDate('appointment_date', $date)
            ->whereIn('status', ['scheduled', 'confirmed', 'in_progress'])
            ->with(['patient', 'doctor']);

        if ($branchId) {
            $appointmentsQuery->where('branch_id', $branchId);
        }

        if ($selectedDoctorId) {
            $appointmentsQuery->where('doctor_id', $selectedDoctorId);
        }

        $appointments = $appointmentsQuery
            ->orderByRaw('CASE WHEN queue_status = "in_room" THEN 0 WHEN queue_status = "called" THEN 1 WHEN queue_status = "waiting" THEN 2 ELSE 3 END')
            ->orderBy('queue_number')
            ->orderBy('appointment_time')
            ->get();

        $waitingCount = $appointments->where('queue_status', 'waiting')->count();
        $inRoomCount = $appointments->where('queue_status', 'in_room')->count();
        $notCheckedIn = $appointments->whereNull('queue_status')->count();

        return view('admin.queue.index', compact(
            'appointments', 'doctors', 'date', 'selectedDoctorId',
            'waitingCount', 'inRoomCount', 'notCheckedIn'
        ));
    }

    public function checkIn(Appointment $appointment)
    {
        $clinic = auth()->user()->clinic;
        abort_if($appointment->clinic_id !== $clinic->id, 403);

        if ($appointment->queue_status !== null) {
            return back()->with('error', __('app.already_checked_in'));
        }

        $queueNumber = Appointment::nextQueueNumber(
            $appointment->doctor_id,
            $appointment->appointment_date->format('Y-m-d')
        );

        $appointment->update([
            'queue_number' => $queueNumber,
            'queue_status' => 'waiting',
            'checked_in_at' => now(),
            'status' => 'confirmed',
        ]);

        return back()->with('success', __('app.patient_checked_in', ['number' => $queueNumber]));
    }

    public function skip(Appointment $appointment)
    {
        $clinic = auth()->user()->clinic;
        abort_if($appointment->clinic_id !== $clinic->id, 403);

        $appointment->update([
            'queue_status' => 'skipped',
        ]);

        return back()->with('success', __('app.patient_skipped'));
    }
}
