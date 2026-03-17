<?php

namespace App\Traits;

use App\Models\AuditLog;

trait Auditable
{
    public static function bootAuditable(): void
    {
        static::created(function ($model) {
            try {
                AuditLog::log('created', $model, null, $model->getAttributes());
            } catch (\Exception $e) {
                // Silently fail to avoid breaking the main operation
            }
        });

        static::updated(function ($model) {
            try {
                $dirty = $model->getDirty();
                if (empty($dirty)) {
                    return;
                }
                $oldValues = [];
                $newValues = [];
                foreach ($dirty as $key => $newValue) {
                    $oldValues[$key] = $model->getOriginal($key);
                    $newValues[$key] = $newValue;
                }
                AuditLog::log('updated', $model, $oldValues, $newValues);
            } catch (\Exception $e) {
                // Silently fail
            }
        });

        static::deleted(function ($model) {
            try {
                AuditLog::log('deleted', $model, $model->getAttributes(), null);
            } catch (\Exception $e) {
                // Silently fail
            }
        });
    }
}
