<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DetectLocation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        // Avoid repeated lookup
        if (!session()->has('location_country')) {

            try {
                $geo = geoip()->getLocation($request->ip());

                session([
                    'location_country' => strtolower(str_replace(' ', '-', $geo->country)),
                    'location_city'    => strtolower(str_replace(' ', '-', $geo->city)),
                ]);

            } catch (\Exception $e) {
                // Fallback
                session([
                    'location_country' => 'global',
                    'location_city'    => 'unknown',
                ]);
            }
        }

        return $next($request);
    }
}
