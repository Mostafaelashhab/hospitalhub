<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    protected $fillable = [
        'clinic_id',
        'branch_id',
        'appointment_id',
        'patient_id',
        'doctor_id',
        'complaint',
        'diagnosis',
        'prescription',
        'lab_tests',
        'radiology',
        'notes',
        'diagram_data',
    ];

    protected function casts(): array
    {
        return [
            'diagram_data' => 'array',
        ];
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function prescription()
    {
        return $this->hasOne(Prescription::class);
    }
}
