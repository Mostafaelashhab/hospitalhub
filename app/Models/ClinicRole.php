<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicRole extends Model
{
    protected $fillable = [
        'clinic_id',
        'slug',
        'name_en',
        'name_ar',
        'is_system',
    ];

    protected function casts(): array
    {
        return [
            'is_system' => 'boolean',
        ];
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function localizedName(): string
    {
        return app()->getLocale() === 'ar' ? $this->name_ar : $this->name_en;
    }

    /**
     * Seed system roles for a clinic.
     */
    public static function seedForClinic(int $clinicId): void
    {
        $systemRoles = [
            ['slug' => 'doctor', 'name_en' => 'Doctor', 'name_ar' => 'دكتور'],
            ['slug' => 'secretary', 'name_en' => 'Secretary', 'name_ar' => 'سكرتير'],
            ['slug' => 'accountant', 'name_en' => 'Accountant', 'name_ar' => 'محاسب'],
        ];

        foreach ($systemRoles as $role) {
            static::firstOrCreate(
                ['clinic_id' => $clinicId, 'slug' => $role['slug']],
                array_merge($role, ['is_system' => true])
            );
        }
    }
}
