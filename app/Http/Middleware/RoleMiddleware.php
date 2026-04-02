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

        // 'clinic_staff' matches admin and staff roles (not doctors — they use /doctor portal)
        if (in_array('clinic_staff', $roles) && $user->clinic_id && $user->role !== 'super_admin') {
            if ($user->role === 'doctor') {
                return redirect()->route('doctor.dashboard');
            }
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
