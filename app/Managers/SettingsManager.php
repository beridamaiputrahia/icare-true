<?php

namespace App\Managers;

use App\Models\AppSetting;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SettingsManager
{
    /**
     * In-memory cache dikelompokkan per tenant_id.
     * Format: [ tenantId => [ key => value ] ]
     * Mencegah bocor antar request di lingkungan long-running (Octane/Queue).
     */
    private static array $cache = [];

    private function tenantId(): string
    {
        return (string) (Auth::check() ? Auth::user()->tenant_id : 'global');
    }

    public function get(string $key, mixed $default = null): mixed
    {
        $tid = $this->tenantId();

        if (! isset(self::$cache[$tid][$key])) {
            self::$cache[$tid][$key] = AppSetting::get($key, $default);
        }

        return self::$cache[$tid][$key] ?? $default;
    }

    public function set(string $key, mixed $value): void
    {
        AppSetting::set($key, $value);
        $tid = $this->tenantId();
        self::$cache[$tid][$key] = $value;
    }

    public function all(): array
    {
        return AppSetting::orderBy('group')->orderBy('sort_order')->get()
            ->keyBy('key')
            ->toArray();
    }

    public function groupedSettings(): \Illuminate\Support\Collection
    {
        return AppSetting::orderBy('group')->orderBy('sort_order')
            ->get()->groupBy('group');
    }

    public function appName(): string
    {
        return $this->get('app_name', config('app.name'));
    }

    public function primaryColor(): string
    {
        return $this->get('primary_color', '#2563eb');
    }

    public function secondaryColor(): string
    {
        return $this->get('secondary_color', '#1d4ed8');
    }

    public function sidebarColor(): string
    {
        return $this->get('sidebar_color', '#1e293b');
    }

    public function logoUrl(): ?string
    {
        $logo = $this->get('logo');
        return $logo ? Storage::url($logo) : null;
    }

    public function faviconUrl(): ?string
    {
        $fav = $this->get('favicon');
        return $fav ? Storage::url($fav) : null;
    }

    public function heroBannerUrl(): ?string
    {
        $banner = $this->get('hero_banner');
        return $banner ? Storage::url($banner) : null;
    }

    public function isMaintenanceMode(): bool
    {
        return (bool) $this->get('maintenance_mode', false);
    }

    public function maintenanceMessage(): string
    {
        return $this->get('maintenance_msg', 'Sistem sedang dalam pemeliharaan.');
    }

    public function maintenanceEta(): string
    {
        return $this->get('maintenance_eta', '');
    }

    public function waLeader(): string
    {
        return $this->get('wa_leader', '');
    }

    public function waCoLeader(): string
    {
        return $this->get('wa_co_leader', '');
    }

    public function flush(): void
    {
        // Hapus in-memory cache hanya untuk tenant saat ini
        $tid = $this->tenantId();
        unset(self::$cache[$tid]);

        AppSetting::flushCache();
    }
}
