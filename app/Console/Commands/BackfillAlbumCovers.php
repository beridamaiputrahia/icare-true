<?php

namespace App\Console\Commands;

use App\Models\Album;
use Illuminate\Console\Command;

/**
 * PhotoController::store() awalnya tidak mengatur cover album saat foto
 * diupload lewat halaman album (bukan saat pembuatan album), jadi album yang
 * dibuat tanpa foto lalu diisi foto belakangan tetap tampil placeholder
 * kosong di Galeri walau sudah punya foto. Command ini dijalankan sekali
 * untuk mengisi cover yang hilang tersebut dari foto pertama tiap album.
 */
class BackfillAlbumCovers extends Command
{
    protected $signature   = 'albums:backfill-covers';
    protected $description = 'Set cover album dari foto pertama untuk album yang belum punya cover';

    public function handle(): int
    {
        $albums = Album::withoutTenantScope()
            ->whereNull('cover')
            ->orWhere('cover', '')
            ->get();

        foreach ($albums as $album) {
            $first = $album->photos()->first();

            if (! $first) {
                continue;
            }

            $album->update(['cover' => $first->file_path]);
            $this->info("Album \"{$album->judul}\": cover di-set dari foto #{$first->id}.");
        }

        $this->info('Selesai.');

        return self::SUCCESS;
    }
}
