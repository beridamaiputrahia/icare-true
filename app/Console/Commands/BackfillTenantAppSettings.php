<?php

namespace App\Console\Commands;

use App\Models\AppSetting;
use App\Models\Tenant;
use Database\Seeders\AppSettingSeeder;
use Illuminate\Console\Command;

/**
 * TenantController::store() awalnya tidak membuat baris app_settings default
 * saat tenant baru dibuat, jadi halaman Pengaturan Aplikasi tampil kosong
 * sejak awal untuk semua tenant yang ada sebelum perbaikan itu ditambahkan.
 * Command ini dijalankan sekali untuk mengisi setting yang hilang tersebut.
 */
class BackfillTenantAppSettings extends Command
{
    protected $signature   = 'settings:backfill-tenants';
    protected $description = 'Buat baris app_settings default untuk tenant yang belum punya';

    public function handle(): int
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $hasSettings = AppSetting::withoutTenantScope()
                ->where('tenant_id', $tenant->id)
                ->exists();

            if (! $hasSettings) {
                (new AppSettingSeeder)->run($tenant->id, $tenant->nama_perusahaan);
                $this->info("Setting default dibuat untuk tenant: {$tenant->nama_perusahaan}");
            }
        }

        $this->info('Selesai.');

        return self::SUCCESS;
    }
}
