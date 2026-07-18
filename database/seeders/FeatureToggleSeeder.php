<?php

namespace Database\Seeders;

use App\Models\Tenant;
use Illuminate\Database\Seeder;

/**
 * Seeder ini kini didelegasikan ke AppSettingSeeder per-tenant.
 * Feature toggle sudah termasuk di dalam AppSettingSeeder.
 * Jalankan ini hanya jika ada tenant yang belum punya setting feature toggle.
 */
class FeatureToggleSeeder extends Seeder
{
    public function run(): void
    {
        $seeder = new AppSettingSeeder();

        // Semai ulang feature toggle untuk semua tenant yang ada
        Tenant::all()->each(function ($tenant) use ($seeder) {
            $seeder->run($tenant->id, $tenant->nama_perusahaan);
        });
    }
}
