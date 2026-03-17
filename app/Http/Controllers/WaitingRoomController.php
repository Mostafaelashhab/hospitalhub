<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Support\Carbon;

class WaitingRoomController extends Controller
{
    public function show(string $slug)
    {
        $clinic = Clinic::where('slug', $slug)->firstOrFail();

        $today = Carbon::today();

        $appointments = $clinic->appointments()
            ->with(['patient', 'doctor.specialty'])
            ->whereDate('appointment_date', $today)
            ->whereIn('queue_status', ['waiting', 'called', 'in_room'])
            ->whereNotNull('queue_number')
            ->orderBy('queue_number')
            ->get();

        // Group by doctor
        $byDoctor = $appointments->groupBy('doctor_id')->map(function ($doctorAppointments) {
            return [
                'doctor'       => $doctorAppointments->first()->doctor,
                'appointments' => $doctorAppointments,
            ];
        })->values();

        return view('waiting-room', [
            'clinic'   => $clinic,
            'byDoctor' => $byDoctor,
        ]);
    }
}
