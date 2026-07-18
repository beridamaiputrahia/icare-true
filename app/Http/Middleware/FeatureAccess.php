<?php

namespace App\Http\Middleware;

use App\Services\FeatureToggleService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware: feature:{nama_fitur}
 *
 * Admin → selalu lolos.
 * ICL/CTL → lolos jika toggle fitur = ON.
 * Anggota → selalu 403.
 *
 * Contoh pemakaian: ->middleware('feature:jadwal')
 */
class FeatureAccess
{
    public function __construct(protected FeatureToggleService $features) {}

    public function handle(Request $request, Closure $next, string $feature): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $this->features->userCan($user, $feature)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Fitur ini tidak aktif.'], 403);
            }
            abort(403, 'Fitur ini tidak aktif atau Anda tidak memiliki izin.');
        }

        return $next($request);
    }
}
