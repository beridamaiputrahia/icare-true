<?php

namespace App\Models\Concerns;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Pasang trait ini di SETIAP model data milik tenant.
 * JANGAN pasang di User dan Tenant.
 *
 * Efek otomatis:
 *  1. Setiap SELECT/UPDATE/DELETE difilter WHERE {table}.tenant_id = tenant user login.
 *  2. Setiap INSERT otomatis mengisi tenant_id dari user login.
 */
trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        // ── Global scope: filter semua query by tenant ──────────────
        static::addGlobalScope('tenant', function (Builder $query) {
            $tenantId = static::resolveTenantId();

            if ($tenantId !== null) {
                // Pakai tabel eksplisit agar tidak ambigu saat JOIN
                $query->where($query->getModel()->getTable() . '.tenant_id', $tenantId);
            }
        });

        // ── Auto-fill tenant_id saat creating ──────────────────────
        static::creating(function (Model $model) {
            if (empty($model->tenant_id)) {
                $model->tenant_id = static::resolveTenantId();
            }
        });
    }

    /**
     * Ambil tenant_id dari user yang sedang login.
     * Kembalikan null jika tidak ada (misal: seeder/artisan tanpa auth).
     */
    protected static function resolveTenantId(): ?int
    {
        if (! Auth::check()) {
            return null;
        }

        return Auth::user()->tenant_id;
    }

    /**
     * Jalankan query TANPA filter tenant (untuk operasi lintas-tenant
     * yang memang disengaja — gunakan dengan hati-hati).
     */
    public static function withoutTenantScope(): Builder
    {
        return static::withoutGlobalScope('tenant');
    }

    // ── Relasi ke Tenant ────────────────────────────────────────────
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
