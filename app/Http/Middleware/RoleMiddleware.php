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

        if (!$user || !in_array($user->role, $roles)) {
            if ($user && $user->role === 'super_admin') {
                return redirect('/super-admin/dashboard');
            }
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
