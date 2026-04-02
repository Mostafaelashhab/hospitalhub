<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TreatmentPlan extends Model
{
    protected $fillable = [
        'clinic_id', 'branch_id', 'patient_id', 'doctor_id',
        'title', 'notes', 'status', 'estimated_total', 'discount',
        'presented_at', 'accepted_at', 'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'estimated_total' => 'decimal:2',
            'discount' => 'decimal:2',
            'presented_at' => 'date',
            'accepted_at' => 'date',
            'completed_at' => 'date',
        ];
    }

    public function clinic()  { return $this->belongsTo(Clinic::class); }
    public function branch()  { return $this->belongsTo(Branch::class); }
    public function patient() { return $this->belongsTo(Patient::class); }
    public function doctor()  { return $this->belongsTo(Doctor::class); }
    public function items()   { return $this->hasMany(TreatmentPlanItem::class)->orderBy('sort_order'); }

    public static function allowedTransitions(): array
    {
        return [
            'draft'       => ['presented'],
            'presented'   => ['accepted', 'rejected'],
            'accepted'    => ['in_progress'],
            'rejected'    => ['draft'],
            'in_progress' => ['completed'],
            'completed'   => [],
        ];
    }

    public function canTransitionTo(string $newStatus): bool
    {
        return in_array($newStatus, static::allowedTransitions()[$this->status] ?? []);
    }

    public function progressPercentage(): int
    {
        $total = $this->items->whereNotIn('status', ['cancelled'])->count();
        if ($total === 0) return 0;
        $completed = $this->items->where('status', 'completed')->count();
        return (int) round(($completed / $total) * 100);
    }

    public function netTotal(): float
    {
        return max(0, $this->estimated_total - $this->discount);
    }

    public function recalculateTotal(): void
    {
        $this->update([
            'estimated_total' => $this->items()->where('status', '!=', 'cancelled')->sum('estimated_cost'),
        ]);
    }
}
