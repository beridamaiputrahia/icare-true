<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
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
        $tid = $tenantId ?? (Auth::check() ? Auth::user()->tenant_id : 'global');
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

    public static function set(string $key, mixed $value): void
    {
        $tenantId = Auth::check() ? Auth::user()->tenant_id : null;

        if ($tenantId) {
            static::withoutTenantScope()->updateOrCreate(
                ['key' => $key, 'tenant_id' => $tenantId],
                ['value' => $value]
            );
            Cache::forget(static::cacheKey($key, $tenantId));
        } else {
            // Konteks non-auth (CLI/queue): update semua tenant untuk key ini
            static::withoutTenantScope()->where('key', $key)->update(['value' => $value]);
            static::withoutTenantScope()->where('key', $key)
                ->each(fn ($s) => Cache::forget(static::cacheKey($s->key, $s->tenant_id)));
        }
    }

    public static function allByGroup(): array
    {
        return static::orderBy('group')->orderBy('sort_order')->get()
            ->groupBy('group')
            ->toArray();
    }

    public static function flushCache(?int $tenantId = null): void
    {
        $tid = $tenantId ?? (Auth::check() ? Auth::user()->tenant_id : null);

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
