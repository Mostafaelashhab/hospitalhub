<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InsuranceProvider extends Model
{
    protected $fillable = [
        'clinic_id',
        'name',
        'phone',
        'email',
        'coverage_percentage',
        'max_coverage',
        'is_active',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'coverage_percentage' => 'decimal:2',
            'max_coverage' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function patientInsurances()
    {
        return $this->hasMany(PatientInsurance::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }
}
