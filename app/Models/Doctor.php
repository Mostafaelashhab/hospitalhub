<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [
        'clinic_id',
        'branch_id',
        'user_id',
        'name',
        'phone',
        'email',
        'specialty_id',
        'consultation_fee',
        'bio',
        'working_days',
        'working_from',
        'working_to',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'consultation_fee' => 'decimal:2',
            'is_active' => 'boolean',
            'working_days' => 'array',
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function diagnoses()
    {
        return $this->hasMany(Diagnosis::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'doctor_service')
            ->withPivot('price', 'is_active')
            ->withTimestamps();
    }
}
