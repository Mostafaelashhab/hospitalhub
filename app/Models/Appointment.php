<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'clinic_id',
        'branch_id',
        'patient_id',
        'doctor_id',
        'appointment_date',
        'appointment_time',
        'status',
        'notes',
        'recurrence_group_id',
        'recurrence_type',
        'recurrence_count',
        'queue_number',
        'queue_status',
        'checked_in_at',
        'called_at',
    ];

    protected function casts(): array
    {
        return [
            'appointment_date' => 'date',
            'appointment_time' => 'datetime:H:i',
            'checked_in_at' => 'datetime',
            'called_at' => 'datetime',
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

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'appointment_service')
            ->withPivot('price')
            ->withTimestamps();
    }

    public function servicesTotal(): float
    {
        return (float) $this->services->sum('pivot.price');
    }

    public function diagnosis()
    {
        return $this->hasOne(Diagnosis::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function recurringGroup()
    {
        return $this->hasMany(Appointment::class, 'recurrence_group_id', 'recurrence_group_id');
    }

    public function isRecurring(): bool
    {
        return $this->recurrence_type !== 'none' && $this->recurrence_group_id !== null;
    }

    public function isCheckedIn(): bool
    {
        return $this->queue_status !== null;
    }

    public function isInQueue(): bool
    {
        return in_array($this->queue_status, ['waiting', 'called']);
    }

    public static function nextQueueNumber(int $doctorId, string $date): int
    {
        $max = static::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $date)
            ->max('queue_number');

        return ($max ?? 0) + 1;
    }

    /**
     * Status transition rules (state machine).
     * Key = current status, Value = array of allowed next statuses.
     */
    public static function allowedTransitions(): array
    {
        return [
            'scheduled'   => ['confirmed', 'cancelled', 'no_show'],
            'confirmed'   => ['in_progress', 'cancelled', 'no_show'],
            'in_progress' => ['completed', 'cancelled'],
            'completed'   => [],  // terminal
            'cancelled'   => [],  // terminal
            'no_show'     => [],  // terminal
        ];
    }

    /**
     * Get allowed next statuses for this appointment.
     */
    public function allowedNextStatuses(): array
    {
        return static::allowedTransitions()[$this->status] ?? [];
    }

    /**
     * Check if transition to a given status is allowed.
     */
    public function canTransitionTo(string $newStatus): bool
    {
        return in_array($newStatus, $this->allowedNextStatuses());
    }

    /**
     * Is this a terminal (final) status?
     */
    public function isTerminal(): bool
    {
        return empty($this->allowedNextStatuses());
    }
}
