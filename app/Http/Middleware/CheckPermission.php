<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, $permission = null): Response
    {
        $user = $request->user();

        // Get route-specific permission from config
        $routeName = $request->route()->getName();
        $routePermissions = config('role_permissions.route_permissions', []);

        // If no specific permission provided, check route mapping
        if (!$permission && isset($routePermissions[$routeName])) {
            $permission = $routePermissions[$routeName];
        }

        // If still no permission, allow access (for public routes)
        if (!$permission) {
            return $next($request);
        }

        // Check if user has permission
        if (!$user || !$user->hasPermission($permission)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            return redirect()->route('home')
                ->with('error', 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
