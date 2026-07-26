<?php

namespace App\Http\Controllers;

use App\Managers\SettingsManager;
use App\Models\AppSetting;
use App\Models\Tenant;
use Database\Seeders\AppSettingSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AppSettingController extends Controller
{
    public function __construct(private SettingsManager $settings) {}

    /**
     * Superadmin yang belum "masuk sebagai" tenant tertentu (belum pilih lewat
     * tenant switcher) tidak punya tenant aktif untuk BelongsToTenant scope --
     * dalam kasus itu, layani pengaturan tenant sistem khusus (bukan grup
     * jemaat sungguhan manapun) supaya superadmin tetap bisa ubah branding
     * dasar aplikasi (nama, logo) tanpa harus pilih grup dulu, dan tanpa
     * menimpa pengaturan grup jemaat asli. Admin biasa selalu punya tenant_id
     * sendiri jadi tidak terpengaruh oleh ini.
     */
    private function resolveTenantIdForSuperadmin(): ?int
    {
        if (auth()->user()->role !== 'superadmin' || session('active_tenant_id')) {
            return null;
        }

        $tenant = Tenant::defaultBrandingTenant();

        if (! AppSetting::withoutTenantScope()->where('tenant_id', $tenant->id)->exists()) {
            (new AppSettingSeeder)->run($tenant->id, $tenant->nama_perusahaan);
        }

        return $tenant->id;
    }

    public function index()
    {
        $tenantId = $this->resolveTenantIdForSuperadmin();

        $query = $tenantId
            ? AppSetting::withoutTenantScope()->where('tenant_id', $tenantId)
            : AppSetting::query();

        $groups = $query->orderBy('group')->orderBy('sort_order')->get()->groupBy('group');
        return view('settings.index', compact('groups'));
    }

    public function update(Request $request)
    {
        $tenantId = $this->resolveTenantIdForSuperadmin();

        // Ambil setting milik tenant yang sedang login (BelongsToTenant scope aktif),
        // atau tenant utama secara eksplisit kalau superadmin belum pilih tenant.
        $query = $tenantId
            ? AppSetting::withoutTenantScope()->where('tenant_id', $tenantId)
            : AppSetting::query();

        $settings = $query->orderBy('sort_order')->get();

        foreach ($settings as $setting) {
            $key = $setting->key;

            if ($setting->type === 'image') {
                if ($request->hasFile($key)) {
                    if ($setting->value) {
                        Storage::disk(config('filesystems.default'))->delete($setting->value);
                    }
                    $path = $request->file($key)->store('settings', config('filesystems.default'));
                    // Update langsung di row yang sudah diketahui & tenant-scoped
                    // (BelongsToTenant) -- JANGAN panggil SettingsManager::set()/
                    // AppSetting::set() di sini, itu re-resolve tenant secara
                    // terpisah dan pernah menyebabkan update bocor ke semua tenant
                    // saat dipanggil superadmin (lihat commit fix cross-tenant leak).
                    $setting->update(['value' => $path]);
                }
                continue;
            }

            if ($setting->type === 'boolean') {
                $value = $request->boolean($key) ? '1' : '0';
                $setting->update(['value' => $value]);
                continue;
            }

            if ($request->has($key)) {
                $value = $request->input($key);
                $setting->update(['value' => $value]);
            }
        }

        $this->settings->flush();

        return redirect()->route('settings.index')
            ->with('success', 'Pengaturan berhasil disimpan.');
    }

    public function maintenanceToggle(Request $request)
    {
        $tenantId = $this->resolveTenantIdForSuperadmin();

        if ($tenantId) {
            $setting = AppSetting::withoutTenantScope()
                ->where('tenant_id', $tenantId)
                ->where('key', 'maintenance_mode')
                ->first();

            $current = (bool) $setting?->value;
            $setting?->update(['value' => $current ? '0' : '1']);
        } else {
            $current = $this->settings->isMaintenanceMode();
            $this->settings->set('maintenance_mode', $current ? '0' : '1');
        }

        $this->settings->flush();

        $status = $current ? 'dinonaktifkan' : 'diaktifkan';
        return back()->with('success', "Mode maintenance berhasil {$status}.");
    }
}
