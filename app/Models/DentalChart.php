<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DentalChart extends Model
{
    protected $fillable = [
        'patient_id',
        'clinic_id',
        'doctor_id',
        'appointment_id',
        'tooth_data',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'tooth_data' => 'array',
        ];
    }

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

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get the status for a specific tooth by FDI number.
     */
    public function toothStatus(string $toothNumber): string
    {
        return $this->tooth_data[$toothNumber]['status'] ?? 'healthy';
    }

    /**
     * All valid FDI tooth numbers for adult dentition.
     */
    public static function allToothNumbers(): array
    {
        return [
            // Upper right quadrant (patient's right = chart left)
            '18', '17', '16', '15', '14', '13', '12', '11',
            // Upper left quadrant
            '21', '22', '23', '24', '25', '26', '27', '28',
            // Lower left quadrant
            '31', '32', '33', '34', '35', '36', '37', '38',
            // Lower right quadrant
            '41', '42', '43', '44', '45', '46', '47', '48',
        ];
    }

    /**
     * All valid statuses.
     */
    public static function statuses(): array
    {
        return [
            'healthy',
            'cavity',
            'filling',
            'crown',
            'extraction',
            'implant',
            'root_canal',
            'bridge',
            'veneer',
            'missing',
        ];
    }
}
