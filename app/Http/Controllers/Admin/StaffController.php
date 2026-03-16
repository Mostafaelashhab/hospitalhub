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
        $staffRoles = $clinic->getStaffRoles();
        $staffRoleSlugs = $staffRoles->pluck('slug')->toArray();

        $query = $clinic->users()->whereIn('role', array_merge(['admin'], $staffRoleSlugs));

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

        return view('admin.staff.index', compact('staff', 'staffRoles'));
    }

    public function create()
    {
        $clinic = auth()->user()->clinic;
        $roles = $clinic->getStaffRoles();
        $branches = $clinic->branches()->where('is_active', true)->get();
        return view('admin.staff.create', compact('roles', 'branches'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => ['required', Password::min(8)],
            'role' => 'required|string|max:50',
            'branch_ids' => 'nullable|array',
            'branch_ids.*' => 'exists:branches,id',
        ]);

        $clinic = auth()->user()->clinic;

        // Validate role exists for this clinic (exclude admin and doctor)
        $validRoles = $clinic->getStaffRoles()->pluck('slug')->toArray();
        if (!in_array($validated['role'], $validRoles)) {
            return back()->withErrors(['role' => 'Invalid role'])->withInput();
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
            'clinic_id' => $clinic->id,
            'is_active' => true,
        ]);

        // Assign branches
        if (!empty($validated['branch_ids'])) {
            $user->branches()->sync($validated['branch_ids']);
        }

        // Set default permissions for this role if not already set
        $this->ensureDefaultPermissions($clinic->id, $validated['role']);

        return redirect()->route('dashboard.staff.index')
            ->with('success', __('app.staff_created'));
    }

    public function edit(User $user)
    {
        $clinic = auth()->user()->clinic;
        abort_if($user->clinic_id !== $clinic->id || $user->role === 'admin', 403);

        $roles = $clinic->getStaffRoles();
        $branches = $clinic->branches()->where('is_active', true)->get();
        $user->load('branches');
        return view('admin.staff.edit', compact('user', 'roles', 'branches'));
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
            'role' => 'required|string|max:50',
            'branch_ids' => 'nullable|array',
            'branch_ids.*' => 'exists:branches,id',
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

        // Sync branches
        $user->branches()->sync($validated['branch_ids'] ?? []);

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
