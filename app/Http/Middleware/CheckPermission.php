<?php

namespace App\Http\Middleware;

use App\Enum\RoleName;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check()) {
            // Admin bypasses all permission checks
            if (auth()->user()->roles()->where('name', RoleName::Admin->value)->exists()) {
                return $next($request);
            }

            $routeName = $request->route()->getName();

            // Check if user has permission via role or directly
            if (!auth()->user()->hasPermission($routeName)) {
                abort(403, 'You do not have permission to access this page.');
            }
        }

        return $next($request);
    }
}
