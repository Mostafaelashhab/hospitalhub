<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorLeave extends Model
{
    protected $fillable = [
        'doctor_id', 'clinic_id', 'start_date', 'end_date',
        'reason', 'status', 'admin_notes', 'cancel_appointments',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'cancel_appointments' => 'boolean',
        ];
    }

    public function doctor() { return $this->belongsTo(Doctor::class); }
    public function clinic() { return $this->belongsTo(Clinic::class); }

    /**
     * Check if a doctor is on approved leave for a given date.
     */
    public static function isOnLeave(int $doctorId, string $date): bool
    {
        return static::where('doctor_id', $doctorId)
            ->where('status', 'approved')
            ->where('start_date', '<=', $date)
            ->where('end_date', '>=', $date)
            ->exists();
    }
}
