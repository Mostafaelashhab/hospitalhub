<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PregnancyVisit extends Model
{
    protected $fillable = [
        'pregnancy_id',
        'visit_date',
        'gestational_week',
        'weight',
        'blood_pressure_systolic',
        'blood_pressure_diastolic',
        'fundal_height',
        'fetal_heart_rate',
        'presentation',
        'notes',
        'next_visit_date',
    ];

    protected function casts(): array
    {
        return [
            'visit_date'      => 'date',
            'next_visit_date' => 'date',
        ];
    }

    public function pregnancy()
    {
        return $this->belongsTo(Pregnancy::class);
    }
}
