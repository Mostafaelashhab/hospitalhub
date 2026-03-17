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
}
