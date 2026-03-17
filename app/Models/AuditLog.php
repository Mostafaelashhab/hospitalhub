<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $fillable = [
        'clinic_id', 'user_id', 'user_name', 'action',
        'model_type', 'model_id', 'model_label',
        'old_values', 'new_values', 'ip_address', 'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'old_values' => 'array',
            'new_values' => 'array',
        ];
    }

    public function user() { return $this->belongsTo(User::class); }
    public function clinic() { return $this->belongsTo(Clinic::class); }

    public static function log(string $action, $model = null, ?array $oldValues = null, ?array $newValues = null): static
    {
        $user = auth()->user();
        return static::create([
            'clinic_id' => $user?->clinic_id,
            'user_id' => $user?->id,
            'user_name' => $user?->name,
            'action' => $action,
            'model_type' => $model ? class_basename($model) : null,
            'model_id' => $model?->id ?? null,
            'model_label' => $model?->name ?? $model?->title ?? ($model?->id ? "#{$model->id}" : null),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
