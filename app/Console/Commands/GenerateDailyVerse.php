<?php

namespace App\Console\Commands;

use App\Models\DailyVerse;
use App\Models\Tenant;
use App\Services\BibleApiService;
use Illuminate\Console\Command;
use Throwable;

/**
 * Buat ayat harian baru otomatis untuk setiap tenant aktif, setiap hari,
 * tanpa perlu admin mengisi manual. Teks ayat diambil live dari API.Bible
 * (lihat BibleApiService) dari daftar referensi populer di
 * config/bible-verses.php.
 *
 * Kalau API.Bible gagal/API key belum diset, tenant tersebut dilewati untuk
 * hari itu — DailyVerse::getToday() akan fallback ke ayat aktif terakhir
 * yang tersedia, jadi tidak ada downtime, cuma tidak dapat ayat baru.
 */
class GenerateDailyVerse extends Command
{
    protected $signature   = 'verse:generate-daily';
    protected $description = 'Buat ayat harian baru otomatis untuk setiap tenant dari API.Bible';

    public function handle(BibleApiService $bibleApi): int
    {
        $today = today();

        $tenants = Tenant::where('is_active', true)->get();

        if ($tenants->isEmpty()) {
            $this->info('Tidak ada tenant aktif.');
            return self::SUCCESS;
        }

        foreach ($tenants as $tenant) {
            $alreadyExists = DailyVerse::withoutTenantScope()
                ->where('tenant_id', $tenant->id)
                ->where('tanggal', $today)
                ->exists();

            if ($alreadyExists) {
                $this->line("Tenant \"{$tenant->nama_perusahaan}\": ayat hari ini sudah ada, dilewati.");
                continue;
            }

            try {
                $verse = $bibleApi->randomVerse();
            } catch (Throwable $e) {
                $this->error("Tenant \"{$tenant->nama_perusahaan}\": gagal ambil ayat dari API.Bible — {$e->getMessage()}");
                continue;
            }

            DailyVerse::withoutTenantScope()->where('tenant_id', $tenant->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);

            DailyVerse::withoutTenantScope()->create([
                'ayat'      => $verse['ayat'],
                'referensi' => $verse['referensi'],
                'tanggal'   => $today,
                'is_active' => true,
                'tenant_id' => $tenant->id,
            ]);

            $this->info("Tenant \"{$tenant->nama_perusahaan}\": ayat baru — {$verse['referensi']}");
        }

        return self::SUCCESS;
    }
}
