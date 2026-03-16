<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ClinicRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    public function index()
    {
        $clinic = auth()->user()->clinic;

        // Ensure system roles exist
        ClinicRole::seedForClinic($clinic->id);

        $clinicRoles = $clinic->clinicRoles()->orderByDesc('is_system')->get();
        $roles = $clinicRoles->pluck('slug');
        $modules = config('permissions.modules');

        // Get all permissions for all roles in this clinic
        $currentPermissions = DB::table('clinic_role_permissions')
            ->where('clinic_id', $clinic->id)
            ->get()
            ->groupBy('role')
            ->map(fn($group) => $group->pluck('permission')->toArray());

        // Ensure defaults are loaded for system roles that don't have any permissions yet
        foreach ($clinicRoles->where('is_system', true) as $clinicRole) {
            if (!$currentPermissions->has($clinicRole->slug)) {
                $defaults = config("permissions.defaults.{$clinicRole->slug}", []);
                if (!empty($defaults)) {
                    $records = collect($defaults)->map(fn($p) => [
                        'clinic_id' => $clinic->id,
                        'role' => $clinicRole->slug,
                        'permission' => $p,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ])->toArray();
                    DB::table('clinic_role_permissions')->insert($records);
                    $currentPermissions[$clinicRole->slug] = $defaults;
                }
            }
        }

        return view('admin.permissions.index', compact('clinicRoles', 'roles', 'modules', 'currentPermissions'));
    }

    public function update(Request $request)
    {
        $clinic = auth()->user()->clinic;
        $validRoles = $clinic->clinicRoles()->pluck('slug')->toArray();
        $allPermissions = collect(config('permissions.modules'))->flatten()->toArray();

        // Delete all existing permissions for non-admin roles
        DB::table('clinic_role_permissions')
            ->where('clinic_id', $clinic->id)
            ->whereIn('role', $validRoles)
            ->delete();

        // Insert new permissions
        $permissions = $request->input('permissions', []);
        $records = [];

        foreach ($permissions as $role => $perms) {
            if (!in_array($role, $validRoles)) continue;

            foreach ($perms as $permission) {
                if (!in_array($permission, $allPermissions)) continue;

                $records[] = [
                    'clinic_id' => $clinic->id,
                    'role' => $role,
                    'permission' => $permission,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        if (!empty($records)) {
            DB::table('clinic_role_permissions')->insert($records);
        }

        return back()->with('success', __('app.permissions_updated'));
    }

    public function storeRole(Request $request)
    {
        $clinic = auth()->user()->clinic;

        $validated = $request->validate([
            'name_en' => 'required|string|max:50',
            'name_ar' => 'required|string|max:50',
        ]);

        $slug = Str::slug($validated['name_en']);

        // Ensure unique slug for this clinic
        $existing = ClinicRole::where('clinic_id', $clinic->id)->where('slug', $slug)->exists();
        if ($existing) {
            return back()->withErrors(['name_en' => __('app.role_already_exists')]);
        }

        ClinicRole::create([
            'clinic_id' => $clinic->id,
            'slug' => $slug,
            'name_en' => $validated['name_en'],
            'name_ar' => $validated['name_ar'],
            'is_system' => false,
        ]);

        return back()->with('success', __('app.role_created'));
    }

    public function destroyRole(ClinicRole $clinicRole)
    {
        $clinic = auth()->user()->clinic;
        abort_if($clinicRole->clinic_id !== $clinic->id, 403);
        abort_if($clinicRole->is_system, 403);

        // Remove permissions for this role
        DB::table('clinic_role_permissions')
            ->where('clinic_id', $clinic->id)
            ->where('role', $clinicRole->slug)
            ->delete();

        // Reset users with this role to secretary
        \App\Models\User::where('clinic_id', $clinic->id)
            ->where('role', $clinicRole->slug)
            ->update(['role' => 'secretary']);

        $clinicRole->delete();

        return back()->with('success', __('app.role_deleted'));
    }
}
