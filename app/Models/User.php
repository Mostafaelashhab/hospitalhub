<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use NotificationChannels\WebPush\HasPushSubscriptions;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasPushSubscriptions;

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

    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'user_branch')->withTimestamps();
    }

    public function hasAccessToBranch(?int $branchId): bool
    {
        if (!$branchId || $this->role === 'super_admin' || $this->role === 'admin') {
            return true;
        }

        // If user has no branches assigned, they can access all
        if ($this->branches()->count() === 0) {
            return true;
        }

        return $this->branches()->where('branches.id', $branchId)->exists();
    }

    public function getAllowedBranchIds(): ?array
    {
        if ($this->role === 'super_admin' || $this->role === 'admin') {
            return null; // null = all branches
        }

        $branchIds = $this->branches()->pluck('branches.id')->toArray();

        // Empty = no restriction (backward compatible)
        return empty($branchIds) ? null : $branchIds;
    }

    public function isSoloDoctorAdmin(): bool
    {
        if ($this->role !== 'doctor' || !$this->clinic_id) {
            return false;
        }

        // Only true if there are no admin users in the clinic
        // (meaning the doctor is the sole manager)
        $hasAdmin = User::where('clinic_id', $this->clinic_id)
            ->where('role', 'admin')
            ->where('is_active', true)
            ->exists();

        return !$hasAdmin && $this->clinic->doctors()->count() <= 1;
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->role === 'super_admin' || $this->role === 'admin') {
            return true;
        }

        // Solo doctor admin (no admin users exist) gets all permissions
        // EXCEPT creating/editing doctors
        if ($this->isSoloDoctorAdmin()) {
            return !in_array($permission, ['doctors.create', 'doctors.edit']);
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
        if ($this->role === 'admin') {
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
