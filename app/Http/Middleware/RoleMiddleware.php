<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized action.');
        }

        $userRole = strtolower(trim(Auth::user()->role));
        $allowedRoles = array_map(fn($r) => strtolower(trim($r)), $roles);

        if (!in_array($userRole, $allowedRoles, true)) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }

}
