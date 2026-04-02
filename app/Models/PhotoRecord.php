<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class PhotoRecord extends Model
{
    protected $fillable = [
        'patient_id',
        'clinic_id',
        'doctor_id',
        'category',
        'tooth_number',
        'label',
        'photo_path',
        'taken_at',
        'notes',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'taken_at' => 'date',
            'sort_order' => 'integer',
        ];
    }

    // Relationships

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    // Accessor

    public function photoUrl(): string
    {
        return Storage::disk('public')->url($this->photo_path);
    }
}
