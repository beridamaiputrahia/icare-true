<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Superadmin selalu lolos, terlepas dari daftar role yang diminta —
        // aksesnya diatur lewat tenant switcher (EnsureTenantSelected), bukan role check.
        if ($user->role === 'superadmin') {
            return $next($request);
        }

        if (!in_array($user->role, $roles)) {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin untuk halaman ini.');
        }

        return $next($request);
    }
}
