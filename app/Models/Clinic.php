<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Clinic extends Model
{
    protected $fillable = [
        'name_en',
        'name_ar',
        'slug',
        'specialty_id',
        'phone',
        'email',
        'logo',
        'address_en',
        'address_ar',
        'city',
        'country',
        'tax_number',
        'points_per_patient',
        'status',
        'free_mode',
        'doctors_count',
        'expected_patients_monthly',
        'clinic_size',
        'working_hours_from',
        'working_hours_to',
        'working_days',
        'working_schedule',
        'has_existing_system',
        'existing_system_name',
        'referral_source',
        'notes',
        'website_enabled',
        'website_primary_color',
        'website_secondary_color',
        'website_about_en',
        'website_about_ar',
        'website_services',
        'website_social_links',
        'website_hero_image',
        'website_meta_description',
        'website_show_doctors',
        'website_show_booking',
    ];

    protected function casts(): array
    {
        return [
            'working_days' => 'array',
            'working_schedule' => 'array',
            'free_mode' => 'boolean',
            'has_existing_system' => 'boolean',
            'website_enabled' => 'boolean',
            'website_services' => 'array',
            'website_social_links' => 'array',
            'website_show_doctors' => 'boolean',
            'website_show_booking' => 'boolean',
        ];
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function admin()
    {
        return $this->hasMany(User::class)->where('role', 'admin');
    }

    public function employees()
    {
        return $this->hasMany(User::class)->where('role', 'employee');
    }

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function wallet()
    {
        return $this->hasOne(ClinicWallet::class);
    }

    public function branches()
    {
        return $this->hasMany(Branch::class);
    }

    public function mainBranch()
    {
        return $this->hasOne(Branch::class)->where('is_main', true);
    }

    public function staff()
    {
        return $this->hasMany(User::class)->whereIn('role', ['accountant', 'secretary']);
    }

    public function insuranceProviders()
    {
        return $this->hasMany(InsuranceProvider::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function seedDefaultPermissions(): void
    {
        $defaults = config('permissions.defaults', []);

        foreach ($defaults as $role => $permissions) {
            if ($role === 'admin' || $permissions === '*') continue;

            $existing = DB::table('clinic_role_permissions')
                ->where('clinic_id', $this->id)
                ->where('role', $role)
                ->exists();

            if (!$existing) {
                $records = collect($permissions)->map(fn($p) => [
                    'clinic_id' => $this->id,
                    'role' => $role,
                    'permission' => $p,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])->toArray();

                DB::table('clinic_role_permissions')->insert($records);
            }
        }
    }
}
