<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'specialty_id',
        'doctor_id',
        'name_en',
        'name_ar',
        'price',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'price' => 'decimal:2',
        ];
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function isCustom(): bool
    {
        return $this->doctor_id !== null;
    }

    public function doctors()
    {
        return $this->belongsToMany(Doctor::class, 'doctor_service')
            ->withPivot('price', 'is_active')
            ->withTimestamps();
    }
}
