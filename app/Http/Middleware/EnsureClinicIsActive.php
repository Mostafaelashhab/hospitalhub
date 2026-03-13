<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureClinicIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->clinic && !$user->clinic->isActive()) {
            if ($user->clinic->status === 'pending') {
                return redirect()->route('dashboard');
            }
            return redirect()->route('clinic.suspended');
        }

        return $next($request);
    }
}
