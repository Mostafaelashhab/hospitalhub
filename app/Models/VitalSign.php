<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VitalSign extends Model
{
    protected $fillable = [
        'clinic_id',
        'patient_id',
        'appointment_id',
        'blood_pressure_systolic',
        'blood_pressure_diastolic',
        'heart_rate',
        'temperature',
        'weight',
        'height',
        'blood_sugar',
        'oxygen_saturation',
        'respiratory_rate',
        'notes',
        'recorded_by',
    ];

    protected function casts(): array
    {
        return [
            'blood_pressure_systolic' => 'decimal:1',
            'blood_pressure_diastolic' => 'decimal:1',
            'heart_rate' => 'decimal:1',
            'temperature' => 'decimal:1',
            'weight' => 'decimal:1',
            'height' => 'decimal:1',
            'blood_sugar' => 'decimal:1',
            'oxygen_saturation' => 'decimal:1',
            'respiratory_rate' => 'decimal:1',
        ];
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function getBmiAttribute(): ?float
    {
        if ($this->weight && $this->height) {
            $heightM = $this->height / 100;
            return round($this->weight / ($heightM * $heightM), 1);
        }
        return null;
    }

    public function getBloodPressureAttribute(): ?string
    {
        if ($this->blood_pressure_systolic && $this->blood_pressure_diastolic) {
            return intval($this->blood_pressure_systolic) . '/' . intval($this->blood_pressure_diastolic);
        }
        return null;
    }
}
