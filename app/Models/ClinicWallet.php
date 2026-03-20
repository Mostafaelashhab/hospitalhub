<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicWallet extends Model
{
    protected $fillable = [
        'clinic_id',
        'balance',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function transactions()
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function credit(int $amount, ?string $description = null, ?string $referenceType = null, ?int $referenceId = null): WalletTransaction
    {
        $this->increment('balance', $amount);

        return $this->transactions()->create([
            'type' => 'credit',
            'amount' => $amount,
            'balance_after' => $this->balance,
            'description' => $description,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'performed_by' => auth()->id(),
        ]);
    }

    public function debit(int $amount, ?string $description = null, ?string $referenceType = null, ?int $referenceId = null): WalletTransaction
    {
        $this->decrement('balance', $amount);

        return $this->transactions()->create([
            'type' => 'debit',
            'amount' => $amount,
            'balance_after' => $this->balance,
            'description' => $description,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'performed_by' => auth()->id(),
        ]);
    }

    public function hasEnoughBalance(int $amount): bool
    {
        return $this->balance >= $amount;
    }
}
