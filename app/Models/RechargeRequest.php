<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RechargeRequest extends Model
{
    protected $fillable = [
        'clinic_id',
        'user_id',
        'points',
        'payment_method',
        'reference_number',
        'status',
        'notes',
        'admin_notes',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
