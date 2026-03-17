<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Pregnancy extends Model
{
    protected $fillable = [
        'patient_id',
        'clinic_id',
        'doctor_id',
        'lmp_date',
        'edd_date',
        'status',
        'delivery_date',
        'delivery_type',
        'baby_gender',
        'baby_weight',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'lmp_date'      => 'date',
            'edd_date'      => 'date',
            'delivery_date' => 'date',
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

    public function visits()
    {
        return $this->hasMany(PregnancyVisit::class)->orderBy('visit_date');
    }

    /**
     * Current gestational week calculated from LMP date.
     */
    public function currentWeek(): int
    {
        if ($this->status !== 'active') {
            if ($this->delivery_date) {
                $days = $this->lmp_date->diffInDays($this->delivery_date);
                return (int) min(floor($days / 7), 42);
            }
        }

        $days = $this->lmp_date->diffInDays(Carbon::today());
        return (int) min(floor($days / 7), 42);
    }

    /**
     * Current trimester (1, 2, or 3).
     */
    public function trimester(): int
    {
        $week = $this->currentWeek();

        if ($week <= 12) {
            return 1;
        } elseif ($week <= 27) {
            return 2;
        }

        return 3;
    }

    /**
     * Days remaining until EDD.
     */
    public function daysRemaining(): int
    {
        if ($this->status !== 'active') {
            return 0;
        }

        $diff = Carbon::today()->diffInDays($this->edd_date, false);
        return max(0, (int) $diff);
    }

    /**
     * Progress percentage (0–100) based on 280 days.
     */
    public function progressPercentage(): float
    {
        $elapsed = $this->lmp_date->diffInDays(Carbon::today());
        return min(100, round(($elapsed / 280) * 100, 1));
    }
}
