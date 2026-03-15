<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalNote extends Model
{
    protected $fillable = [
        'clinic_id',
        'patient_id',
        'appointment_id',
        'note',
        'type',
        'created_by',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
