<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'clinic_id',
        'role',
        'phone',
        'avatar',
        'is_active',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }

    public function isDoctor(): bool
    {
        return $this->role === 'doctor';
    }

    public function isPatient(): bool
    {
        return $this->role === 'patient';
    }

    public function isStaff(): bool
    {
        return in_array($this->role, ['admin', 'doctor', 'accountant', 'secretary']);
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    public function isSoloDoctorAdmin(): bool
    {
        if ($this->role !== 'doctor' || !$this->clinic_id) {
            return false;
        }

        return $this->clinic->doctors()->count() <= 1;
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->role === 'super_admin' || $this->role === 'admin') {
            return true;
        }

        if ($this->role === 'doctor' && $this->isSoloDoctorAdmin()) {
            return true;
        }

        if (!$this->clinic_id) {
            return false;
        }

        return DB::table('clinic_role_permissions')
            ->where('clinic_id', $this->clinic_id)
            ->where('role', $this->role)
            ->where('permission', $permission)
            ->exists();
    }

    public function getPermissions(): array
    {
        if ($this->role === 'admin' || ($this->role === 'doctor' && $this->isSoloDoctorAdmin())) {
            return ['*'];
        }

        if (!$this->clinic_id) {
            return [];
        }

        return DB::table('clinic_role_permissions')
            ->where('clinic_id', $this->clinic_id)
            ->where('role', $this->role)
            ->pluck('permission')
            ->toArray();
    }
}
