<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackOnlineStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if ($user && (! $user->last_seen || $user->last_seen->lt(now()->subMinute()))) {
            $user->markOnline();
        }

        return $next($request);
    }
}
