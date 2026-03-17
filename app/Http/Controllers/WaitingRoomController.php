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

        // Show all today's appointments except cancelled
        $appointments = $clinic->appointments()
            ->with(['patient', 'doctor.specialty', 'doctor.user'])
            ->whereDate('appointment_date', $today)
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->orderByRaw("
                CASE
                    WHEN queue_status = 'in_room' THEN 1
                    WHEN status = 'in_progress' THEN 2
                    WHEN queue_status = 'called' THEN 3
                    WHEN queue_status = 'waiting' THEN 4
                    WHEN status = 'confirmed' THEN 5
                    WHEN status = 'scheduled' THEN 6
                    WHEN status = 'completed' THEN 7
                    ELSE 8
                END
            ")
            ->orderBy('queue_number')
            ->orderBy('appointment_time')
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
