<?php

namespace App\Http\Middleware;

use App\Managers\SettingsManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MaintenanceMiddleware
{
    public function __construct(private SettingsManager $settings) {}

    public function handle(Request $request, Closure $next): Response
    {
        if ($this->settings->isMaintenanceMode()) {
            // Admin can bypass maintenance mode
            if (auth()->check() && auth()->user()->isAdmin()) {
                return $next($request);
            }

            // Allow access to login and maintenance routes
            if ($request->routeIs('login', 'logout', 'maintenance.*')) {
                return $next($request);
            }

            return response()->view('errors.maintenance', [
                'message' => $this->settings->maintenanceMessage(),
                'eta'     => $this->settings->maintenanceEta(),
            ], 503);
        }

        return $next($request);
    }
}
