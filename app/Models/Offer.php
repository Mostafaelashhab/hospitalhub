<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Offer extends Model
{
    protected $fillable = [
        'created_by',
        'title_en',
        'title_ar',
        'description_en',
        'description_ar',
        'type',
        'discount_percentage',
        'discount_amount',
        'image',
        'start_date',
        'end_date',
        'is_active',
        'for_all_clinics',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'for_all_clinics' => 'boolean',
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function clinics(): BelongsToMany
    {
        return $this->belongsToMany(Clinic::class, 'clinic_offer');
    }

    public function getTitleAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->title_ar : $this->title_en;
    }

    public function getDescriptionAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? $this->description_ar : $this->description_en;
    }

    public function isExpired(): bool
    {
        return $this->end_date->isPast();
    }

    public function isRunning(): bool
    {
        return $this->is_active && $this->start_date->lte(now()) && $this->end_date->gte(now());
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    public function scopeForClinic($query, $clinicId)
    {
        return $query->where(function ($q) use ($clinicId) {
            $q->where('for_all_clinics', true)
                ->orWhereHas('clinics', fn ($c) => $c->where('clinics.id', $clinicId));
        });
    }
}
