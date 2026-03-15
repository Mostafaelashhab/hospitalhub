<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    protected $fillable = [
        'clinic_id',
        'name_en',
        'name_ar',
        'icon',
        'color',
        'is_default',
    ];

    protected function casts(): array
    {
        return [
            'is_default' => 'boolean',
        ];
    }

    public function getNameAttribute(): string
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Seed default categories for a clinic.
     */
    public static function seedDefaults(int $clinicId): void
    {
        $defaults = [
            ['name_en' => 'Rent', 'name_ar' => 'إيجار', 'icon' => 'home', 'color' => '#6366f1'],
            ['name_en' => 'Salaries', 'name_ar' => 'رواتب', 'icon' => 'users', 'color' => '#10b981'],
            ['name_en' => 'Medical Supplies', 'name_ar' => 'مستلزمات طبية', 'icon' => 'briefcase', 'color' => '#f59e0b'],
            ['name_en' => 'Utilities', 'name_ar' => 'مرافق (كهرباء/مياه)', 'icon' => 'zap', 'color' => '#ef4444'],
            ['name_en' => 'Maintenance', 'name_ar' => 'صيانة', 'icon' => 'tool', 'color' => '#8b5cf6'],
            ['name_en' => 'Marketing', 'name_ar' => 'تسويق', 'icon' => 'megaphone', 'color' => '#ec4899'],
            ['name_en' => 'Equipment', 'name_ar' => 'أجهزة ومعدات', 'icon' => 'cpu', 'color' => '#06b6d4'],
            ['name_en' => 'Other', 'name_ar' => 'أخرى', 'icon' => 'more-horizontal', 'color' => '#64748b'],
        ];

        foreach ($defaults as $cat) {
            static::create(array_merge($cat, [
                'clinic_id' => $clinicId,
                'is_default' => true,
            ]));
        }
    }
}
