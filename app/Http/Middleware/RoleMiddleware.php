<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = User::with('roles')->find(Auth::id());

        // Check if user has any of the allowed roles
        $hasRole = $user->roles()->whereIn('role_name', $roles)->exists();

        if (!$user || !$hasRole) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);


    }
}
