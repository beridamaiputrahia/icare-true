<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AppSetting extends Model
{
    use BelongsToTenant;

    protected $table = 'app_settings';

    protected $fillable = ['key', 'value', 'type', 'label', 'group', 'sort_order', 'tenant_id'];

    /**
     * Cache key unik per tenant agar setting tenant A tidak bocor ke tenant B.
     * Format: setting_{tenantId}_{key}
     */
    private static function cacheKey(string $key, ?int $tenantId = null): string
    {
        $tid = $tenantId ?? static::resolveTenantId() ?? 'global';
        return "setting_{$tid}_{$key}";
    }

    /**
     * Ambil setting untuk tenant yang sedang login.
     * Jika dipanggil tanpa auth (misal manifest.json), tenantId bisa dioper manual.
     */
    public static function get(string $key, mixed $default = null, ?int $tenantId = null): mixed
    {
        $cacheKey = static::cacheKey($key, $tenantId);

        return Cache::remember($cacheKey, 3600, function () use ($key, $default, $tenantId) {
            // Jika tenantId dioper manual (route tanpa auth), query langsung tanpa scope
            if ($tenantId !== null) {
                $setting = static::withoutTenantScope()
                                 ->where('tenant_id', $tenantId)
                                 ->where('key', $key)
                                 ->first();
            } else {
                // Pakai global scope BelongsToTenant (butuh user login)
                $setting = static::where('key', $key)->first();
            }

            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set value untuk SATU tenant (tenant user yang sedang login/aktif).
     * Pakai resolveTenantId() dari BelongsToTenant -- ini juga menangani
     * superadmin yang sedang "masuk sebagai" tenant tertentu lewat tenant
     * switcher (session active_tenant_id), bukan cuma kolom users.tenant_id
     * yang untuk superadmin memang selalu null by design.
     */
    public static function set(string $key, mixed $value): void
    {
        $tenantId = static::resolveTenantId();

        if ($tenantId) {
            static::withoutTenantScope()->updateOrCreate(
                ['key' => $key, 'tenant_id' => $tenantId],
                ['value' => $value]
            );
            Cache::forget(static::cacheKey($key, $tenantId));

            return;
        }

        // Tidak ada tenant aktif yang bisa ditentukan (mis. superadmin belum
        // pilih tenant, atau dipanggil tanpa auth) -- jangan mass-update semua
        // tenant secara diam-diam. Pemanggil CLI/queue yang sungguh perlu
        // update lintas tenant harus pakai setForAllTenants() secara eksplisit.
    }

    /**
     * Update value untuk SEMUA tenant sekaligus. Hanya untuk dipakai secara
     * SENGAJA oleh job CLI/queue (mis. broadcast pengumuman sistem) -- jangan
     * dipanggil dari alur request web biasa.
     */
    public static function setForAllTenants(string $key, mixed $value): void
    {
        static::withoutTenantScope()->where('key', $key)->update(['value' => $value]);
        static::withoutTenantScope()->where('key', $key)
            ->each(fn ($s) => Cache::forget(static::cacheKey($key, $s->tenant_id)));
    }

    public static function allByGroup(): array
    {
        return static::orderBy('group')->orderBy('sort_order')->get()
            ->groupBy('group')
            ->toArray();
    }

    public static function flushCache(?int $tenantId = null): void
    {
        $tid = $tenantId ?? static::resolveTenantId();

        if ($tid) {
            // Flush hanya cache tenant ini
            static::withoutTenantScope()
                  ->where('tenant_id', $tid)
                  ->each(fn ($s) => Cache::forget(static::cacheKey($s->key, $tid)));
        } else {
            static::withoutTenantScope()
                  ->each(fn ($s) => Cache::forget(static::cacheKey($s->key, $s->tenant_id)));
        }
    }
}
