<?php

namespace App\Console\Commands;

use App\Models\Album;
use App\Models\Photo;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Sebelum perbaikan limit upload PHP (post_max_size/upload_max_filesize),
 * foto besar gagal diam-diam di level PHP: baris Photo tetap tercatat di
 * database (Photo::create() jalan normal), tapi file fisiknya tidak pernah
 * benar-benar tersimpan lengkap di Cloudinary -- hasilnya broken image di
 * galeri. Command ini mengecek setiap foto terhadap disk Cloudinary yang
 * sesungguhnya dan menghapus baris yang file-nya memang tidak ada, plus
 * membersihkan cover album yang menunjuk ke foto rusak tersebut.
 *
 * Jalankan dengan --dry-run dulu untuk lihat apa yang AKAN dihapus tanpa
 * benar-benar menghapus apa pun.
 */
class CleanupBrokenPhotos extends Command
{
    protected $signature   = 'photos:cleanup-broken {--dry-run : Hanya tampilkan, tidak menghapus apa pun}';
    protected $description = 'Hapus baris Photo yang file-nya tidak ada di storage (upload gagal diam-diam)';

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');
        $disk   = Storage::disk(config('filesystems.default'));

        $photos = Photo::withoutTenantScope()->get();
        $brokenCount = 0;

        foreach ($photos as $photo) {
            if ($disk->exists($photo->file_path)) {
                continue;
            }

            $brokenCount++;
            $this->warn(($dryRun ? '[DRY RUN] Akan dihapus' : 'Menghapus') . ": Photo #{$photo->id} (album #{$photo->album_id}) — {$photo->file_path}");

            if (! $dryRun) {
                $photo->delete();
            }
        }

        // Bersihkan cover album yang menunjuk ke path yang tidak ada di disk
        $albums = Album::withoutTenantScope()->whereNotNull('cover')->get();
        $brokenCovers = 0;

        foreach ($albums as $album) {
            if ($disk->exists($album->cover)) {
                continue;
            }

            $brokenCovers++;
            $this->warn(($dryRun ? '[DRY RUN] Cover akan direset' : 'Cover direset') . ": Album #{$album->id} ({$album->judul}) — {$album->cover}");

            if (! $dryRun) {
                $newCover = $album->photos()->first()?->file_path;
                $album->update(['cover' => $newCover]);
            }
        }

        $this->info("Selesai. {$brokenCount} foto rusak" . ($dryRun ? ' ditemukan' : ' dihapus') . ", {$brokenCovers} cover album " . ($dryRun ? 'perlu direset' : 'direset') . ".");

        return self::SUCCESS;
    }
}
