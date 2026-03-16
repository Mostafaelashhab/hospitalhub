<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403, 'Unauthorized.');
        }

        // 'clinic_staff' matches any non-super_admin user with a clinic
        if (in_array('clinic_staff', $roles) && $user->clinic_id && $user->role !== 'super_admin') {
            return $next($request);
        }

        if (!in_array($user->role, $roles)) {
            if ($user->role === 'super_admin') {
                return redirect('/super-admin/dashboard');
            }
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
