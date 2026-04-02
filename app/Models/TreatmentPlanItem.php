<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TreatmentPlanItem extends Model
{
    protected $fillable = [
        'treatment_plan_id', 'service_id', 'appointment_id',
        'tooth_number', 'description', 'estimated_cost',
        'status', 'sort_order', 'notes', 'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'estimated_cost' => 'decimal:2',
            'completed_at' => 'date',
        ];
    }

    public function treatmentPlan() { return $this->belongsTo(TreatmentPlan::class); }
    public function service()       { return $this->belongsTo(Service::class); }
    public function appointment()   { return $this->belongsTo(Appointment::class); }
}
