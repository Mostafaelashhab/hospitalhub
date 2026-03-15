<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientLedgerEntry extends Model
{
    protected $fillable = [
        'clinic_id',
        'patient_id',
        'invoice_id',
        'type',
        'amount',
        'balance_after',
        'description',
        'payment_method',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'balance_after' => 'decimal:2',
        ];
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the current balance for a patient in a clinic.
     */
    public static function getBalance(int $clinicId, int $patientId): float
    {
        $last = static::where('clinic_id', $clinicId)
            ->where('patient_id', $patientId)
            ->latest()
            ->first();

        return $last ? (float) $last->balance_after : 0;
    }

    /**
     * Add a debit entry (patient owes money).
     */
    public static function addDebit(int $clinicId, int $patientId, float $amount, ?string $description = null, ?int $invoiceId = null, ?int $createdBy = null): static
    {
        $balance = static::getBalance($clinicId, $patientId);
        $newBalance = $balance + $amount;

        return static::create([
            'clinic_id' => $clinicId,
            'patient_id' => $patientId,
            'invoice_id' => $invoiceId,
            'type' => 'debit',
            'amount' => $amount,
            'balance_after' => $newBalance,
            'description' => $description,
            'created_by' => $createdBy,
        ]);
    }

    /**
     * Add a credit entry (patient paid money).
     */
    public static function addCredit(int $clinicId, int $patientId, float $amount, ?string $description = null, ?string $paymentMethod = null, ?int $invoiceId = null, ?int $createdBy = null): static
    {
        $balance = static::getBalance($clinicId, $patientId);
        $newBalance = $balance - $amount;

        return static::create([
            'clinic_id' => $clinicId,
            'patient_id' => $patientId,
            'invoice_id' => $invoiceId,
            'type' => 'credit',
            'amount' => $amount,
            'balance_after' => $newBalance,
            'description' => $description,
            'payment_method' => $paymentMethod,
            'created_by' => $createdBy,
        ]);
    }
}
