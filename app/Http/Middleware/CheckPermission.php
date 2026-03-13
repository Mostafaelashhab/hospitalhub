<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (!$user) {
            abort(403);
        }

        // Super admin bypasses all permission checks
        if ($user->role === 'super_admin') {
            return $next($request);
        }

        // Admin always has all permissions for their clinic
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Check if user has the required permission for their clinic
        if (!$user->hasPermission($permission)) {
            abort(403, __('app.no_permission'));
        }

        return $next($request);
    }
}
