<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Superadmin tidak terikat tenant manapun (tenant_id null), jadi setiap
 * query BelongsToTenant butuh tenant "aktif" dari session. Middleware ini
 * memastikan superadmin sudah memilih tenant sebelum mengakses halaman
 * data (jadwal, anggota, dll) — kalau belum, redirect ke halaman pilih tenant.
 */
class EnsureTenantSelected
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->role === 'superadmin' && ! session('active_tenant_id')) {
            return redirect()->route('superadmin.tenants.select');
        }

        return $next($request);
    }
}
