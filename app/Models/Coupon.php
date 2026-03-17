<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'clinic_id', 'code', 'name_en', 'name_ar', 'type', 'value',
        'min_amount', 'max_discount', 'max_uses', 'max_uses_per_patient',
        'used_count', 'valid_from', 'valid_to', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'decimal:2',
            'min_amount' => 'decimal:2',
            'max_discount' => 'decimal:2',
            'valid_from' => 'date',
            'valid_to' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function clinic() { return $this->belongsTo(Clinic::class); }
    public function usages() { return $this->hasMany(CouponUsage::class); }

    public function isValid(?float $invoiceAmount = null, ?int $patientId = null): bool
    {
        if (!$this->is_active) return false;
        if ($this->valid_from && now()->lt($this->valid_from)) return false;
        if ($this->valid_to && now()->gt($this->valid_to)) return false;
        if ($this->max_uses && $this->used_count >= $this->max_uses) return false;
        if ($invoiceAmount && $this->min_amount && $invoiceAmount < $this->min_amount) return false;
        if ($patientId && $this->max_uses_per_patient) {
            $patientUses = $this->usages()->where('patient_id', $patientId)->count();
            if ($patientUses >= $this->max_uses_per_patient) return false;
        }
        return true;
    }

    public function calculateDiscount(float $amount): float
    {
        $discount = $this->type === 'percentage'
            ? $amount * ($this->value / 100)
            : $this->value;
        if ($this->max_discount && $discount > $this->max_discount) {
            $discount = $this->max_discount;
        }
        return min($discount, $amount);
    }
}
