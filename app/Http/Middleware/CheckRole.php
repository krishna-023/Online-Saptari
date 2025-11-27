<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user || !in_array($user->role, $roles)) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Unauthorized role access'], 403);
            }

            return redirect()->route('home')
                ->with('error', 'You do not have the required role to access this page.');
        }

        return $next($request);
    }
}
