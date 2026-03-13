<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermissionController extends Controller
{
    public function index()
    {
        $clinic = auth()->user()->clinic;
        $roles = collect(config('permissions.roles'))->filter(fn($r) => $r !== 'admin')->values();
        $modules = config('permissions.modules');

        // Get all permissions for all roles in this clinic
        $currentPermissions = DB::table('clinic_role_permissions')
            ->where('clinic_id', $clinic->id)
            ->get()
            ->groupBy('role')
            ->map(fn($group) => $group->pluck('permission')->toArray());

        // Ensure defaults are loaded for roles that don't have any permissions yet
        foreach ($roles as $role) {
            if (!$currentPermissions->has($role)) {
                $defaults = config("permissions.defaults.{$role}", []);
                if (!empty($defaults)) {
                    $records = collect($defaults)->map(fn($p) => [
                        'clinic_id' => $clinic->id,
                        'role' => $role,
                        'permission' => $p,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ])->toArray();
                    DB::table('clinic_role_permissions')->insert($records);
                    $currentPermissions[$role] = $defaults;
                }
            }
        }

        return view('admin.permissions.index', compact('roles', 'modules', 'currentPermissions'));
    }

    public function update(Request $request)
    {
        $clinic = auth()->user()->clinic;
        $roles = collect(config('permissions.roles'))->filter(fn($r) => $r !== 'admin')->values();
        $allPermissions = collect(config('permissions.modules'))->flatten()->toArray();

        // Delete all existing permissions for non-admin roles
        DB::table('clinic_role_permissions')
            ->where('clinic_id', $clinic->id)
            ->whereIn('role', $roles->toArray())
            ->delete();

        // Insert new permissions
        $permissions = $request->input('permissions', []);
        $records = [];

        foreach ($permissions as $role => $perms) {
            if (!$roles->contains($role)) continue;

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
}
