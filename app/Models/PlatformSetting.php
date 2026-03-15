<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlatformSetting extends Model
{
    protected $fillable = ['key', 'value'];

    public static function get(string $key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['key' => $key], ['value' => $value]);
    }

    public static function isFreeModeActive(?Clinic $clinic = null): bool
    {
        // Per-clinic free mode
        if ($clinic && $clinic->free_mode) {
            return true;
        }

        // Global free mode
        $enabled = static::get('free_mode_enabled', '0');
        if ($enabled !== '1') {
            return false;
        }

        $until = static::get('free_mode_until');
        if ($until && now()->gt($until)) {
            return false;
        }

        return true;
    }

    public static function getPointPrice(): float
    {
        return (float) static::get('point_price', 1);
    }
}
