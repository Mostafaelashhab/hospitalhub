<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class StaffController extends Controller
{
    public function index(Request $request)
    {
        $clinic = auth()->user()->clinic;

        $query = $clinic->users()->whereIn('role', ['admin', 'accountant', 'secretary']);

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $staff = $query->latest()->paginate(15);

        return view('admin.staff.index', compact('staff'));
    }

    public function create()
    {
        $roles = collect(config('permissions.roles'))->filter(fn($r) => !in_array($r, ['admin', 'doctor']));
        return view('admin.staff.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', Password::min(8)],
            'role' => 'required|in:accountant,secretary',
        ]);

        $clinic = auth()->user()->clinic;

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'clinic_id' => $clinic->id,
            'is_active' => true,
        ]);

        // Set default permissions for this role if not already set
        $this->ensureDefaultPermissions($clinic->id, $validated['role']);

        return redirect()->route('dashboard.staff.index')
            ->with('success', __('app.staff_created'));
    }

    public function edit(User $user)
    {
        $clinic = auth()->user()->clinic;
        abort_if($user->clinic_id !== $clinic->id || $user->role === 'admin', 403);

        $roles = collect(config('permissions.roles'))->filter(fn($r) => !in_array($r, ['admin', 'doctor']));
        return view('admin.staff.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $clinic = auth()->user()->clinic;
        abort_if($user->clinic_id !== $clinic->id || $user->role === 'admin', 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => ['nullable', Password::min(8)],
            'role' => 'required|in:accountant,secretary',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'role' => $validated['role'],
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        $this->ensureDefaultPermissions($clinic->id, $validated['role']);

        return redirect()->route('dashboard.staff.index')
            ->with('success', __('app.staff_updated'));
    }

    public function toggleStatus(User $user)
    {
        $clinic = auth()->user()->clinic;
        abort_if($user->clinic_id !== $clinic->id || $user->role === 'admin', 403);

        $user->update(['is_active' => !$user->is_active]);

        return back()->with('success', __('app.status_updated'));
    }

    private function ensureDefaultPermissions(int $clinicId, string $role): void
    {
        $defaults = config("permissions.defaults.{$role}", []);
        if (empty($defaults)) {
            return;
        }

        $existing = \DB::table('clinic_role_permissions')
            ->where('clinic_id', $clinicId)
            ->where('role', $role)
            ->pluck('permission')
            ->toArray();

        if (empty($existing)) {
            $records = collect($defaults)->map(fn($p) => [
                'clinic_id' => $clinicId,
                'role' => $role,
                'permission' => $p,
                'created_at' => now(),
                'updated_at' => now(),
            ])->toArray();

            \DB::table('clinic_role_permissions')->insert($records);
        }
    }
}
