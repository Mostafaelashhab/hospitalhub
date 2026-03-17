<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiagnosisTemplate extends Model
{
    protected $fillable = [
        'doctor_id', 'clinic_id', 'name', 'complaint', 'diagnosis',
        'prescription', 'lab_tests', 'radiology', 'notes',
        'diagram_data', 'rx_drugs', 'usage_count',
    ];

    protected function casts(): array
    {
        return [
            'diagram_data' => 'array',
            'rx_drugs' => 'array',
        ];
    }

    public function doctor() { return $this->belongsTo(Doctor::class); }
    public function clinic() { return $this->belongsTo(Clinic::class); }
}
