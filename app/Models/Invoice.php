<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'clinic_id',
        'branch_id',
        'patient_id',
        'appointment_id',
        'insurance_provider_id',
        'amount',
        'discount',
        'insurance_coverage',
        'patient_share',
        'total',
        'status',
        'payment_method',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'discount' => 'decimal:2',
            'insurance_coverage' => 'decimal:2',
            'patient_share' => 'decimal:2',
            'total' => 'decimal:2',
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

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function insuranceProvider()
    {
        return $this->belongsTo(InsuranceProvider::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
