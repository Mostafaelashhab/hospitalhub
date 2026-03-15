<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChronicDisease extends Model
{
    protected $fillable = [
        'clinic_id',
        'patient_id',
        'disease_name',
        'diagnosed_date',
        'severity',
        'notes',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'diagnosed_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
