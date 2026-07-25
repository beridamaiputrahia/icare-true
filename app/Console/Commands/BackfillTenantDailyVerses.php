<?php

namespace App\Console\Commands;

use App\Models\DailyVerse;
use App\Models\Tenant;
use App\Services\BibleApiService;
use Illuminate\Console\Command;
use Throwable;

/**
 * TenantController::store() awalnya tidak membuat ayat harian pertama saat
 * tenant baru dibuat, jadi tenant tersebut tampil kosong sampai giliran
 * verse:generate-daily berikutnya jam 06:00 -- bisa lewat berhari-hari kalau
 * scheduler tidak terpicu tepat waktu. Command ini dijalankan sekali untuk
 * mengisi ayat harian yang hilang tersebut.
 */
class BackfillTenantDailyVerses extends Command
{
    protected $signature   = 'verse:backfill-tenants';
    protected $description = 'Buat ayat harian pertama untuk tenant yang belum punya sama sekali';

    public function handle(BibleApiService $bibleApi): int
    {
        $tenants = Tenant::all();

        foreach ($tenants as $tenant) {
            $hasAny = DailyVerse::withoutTenantScope()
                ->where('tenant_id', $tenant->id)
                ->exists();

            if ($hasAny) {
                continue;
            }

            try {
                $verse = $bibleApi->randomVerse();
            } catch (Throwable $e) {
                $this->error("Tenant \"{$tenant->nama_perusahaan}\": gagal ambil ayat dari API.Bible — {$e->getMessage()}");
                continue;
            }

            DailyVerse::withoutTenantScope()->create([
                'ayat'      => $verse['ayat'],
                'referensi' => $verse['referensi'],
                'tanggal'   => today(),
                'is_active' => true,
                'tenant_id' => $tenant->id,
            ]);

            $this->info("Tenant \"{$tenant->nama_perusahaan}\": ayat pertama dibuat — {$verse['referensi']}");
        }

        $this->info('Selesai.');

        return self::SUCCESS;
    }
}
