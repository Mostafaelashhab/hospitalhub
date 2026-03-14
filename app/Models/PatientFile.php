<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientFile extends Model
{
    protected $fillable = [
        'clinic_id',
        'patient_id',
        'uploaded_by',
        'name',
        'file_path',
        'file_type',
        'file_size',
        'category',
        'notes',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function isImage(): bool
    {
        return str_starts_with($this->file_type, 'image/');
    }

    public function isPdf(): bool
    {
        return $this->file_type === 'application/pdf';
    }

    public function formattedSize(): string
    {
        if ($this->file_size >= 1048576) {
            return round($this->file_size / 1048576, 1) . ' MB';
        }
        return round($this->file_size / 1024, 1) . ' KB';
    }
}
