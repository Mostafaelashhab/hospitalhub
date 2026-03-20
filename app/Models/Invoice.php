<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'clinic_id',
        'branch_id',
        'patient_id',
        'appointment_id',
        'insurance_provider_id',
        'coupon_id',
        'amount',
        'discount',
        'insurance_coverage',
        'patient_share',
        'total',
        'paid_amount',
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
            'paid_amount' => 'decimal:2',
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

    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    public static function allowedTransitions(): array
    {
        return [
            'unpaid'   => ['paid', 'partial', 'refunded'],
            'partial'  => ['paid', 'refunded'],
            'paid'     => ['refunded'],
            'refunded' => [],
        ];
    }

    public function canTransitionTo(string $newStatus): bool
    {
        $allowed = static::allowedTransitions()[$this->status] ?? [];
        return in_array($newStatus, $allowed);
    }

    /**
     * Recalculate insurance coverage based on the linked provider.
     */
    public function calculateInsurance(float $subtotal): array
    {
        $insuranceCoverage = 0;
        $patientShare = max(0, $subtotal);

        if ($this->insurance_provider_id) {
            $provider = $this->insuranceProvider;
            if ($provider) {
                $insuranceCoverage = $subtotal * ($provider->coverage_percentage / 100);
                if ($provider->max_coverage && $insuranceCoverage > $provider->max_coverage) {
                    $insuranceCoverage = $provider->max_coverage;
                }
                $insuranceCoverage = max(0, round($insuranceCoverage, 2));
                $patientShare = max(0, $subtotal - $insuranceCoverage);
            }
        }

        return [
            'insurance_coverage' => $insuranceCoverage,
            'patient_share' => $patientShare,
        ];
    }

    public function couponUsage()
    {
        return $this->hasOne(CouponUsage::class);
    }
}
