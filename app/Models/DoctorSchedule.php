<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    protected $fillable = [
        'doctor_id', 'day', 'start_time', 'end_time', 'slot_duration', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'slot_duration' => 'integer',
        ];
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get available time slots for this schedule period.
     */
    public function getSlots(): array
    {
        $slots = [];
        $start = Carbon::parse($this->start_time);
        $end = Carbon::parse($this->end_time);

        while ($start->copy()->addMinutes($this->slot_duration)->lte($end)) {
            $slots[] = $start->format('H:i');
            $start->addMinutes($this->slot_duration);
        }

        return $slots;
    }

    /**
     * Get all available slots for a doctor on a given date.
     */
    public static function getAvailableSlots(int $doctorId, string $date): array
    {
        $dayName = strtolower(Carbon::parse($date)->format('D')); // sat, sun, etc.

        $schedules = static::where('doctor_id', $doctorId)
            ->where('day', $dayName)
            ->where('is_active', true)
            ->orderBy('start_time')
            ->get();

        if ($schedules->isEmpty()) {
            return [];
        }

        // Get already booked times
        $booked = Appointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $date)
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->pluck('appointment_time')
            ->map(fn($t) => Carbon::parse($t)->format('H:i'))
            ->toArray();

        $slots = [];
        foreach ($schedules as $schedule) {
            foreach ($schedule->getSlots() as $slot) {
                $slots[] = [
                    'time' => $slot,
                    'label' => Carbon::parse($slot)->format('g:i A'),
                    'available' => !in_array($slot, $booked),
                    'period' => $schedule->start_time . ' - ' . $schedule->end_time,
                ];
            }
        }

        return $slots;
    }
}
