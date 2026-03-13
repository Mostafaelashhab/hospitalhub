<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WalletTransaction extends Model
{
    protected $fillable = [
        'clinic_wallet_id',
        'type',
        'amount',
        'balance_after',
        'description',
        'reference_type',
        'reference_id',
    ];

    public function wallet()
    {
        return $this->belongsTo(ClinicWallet::class, 'clinic_wallet_id');
    }
}
