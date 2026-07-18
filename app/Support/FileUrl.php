<?php

namespace App\Support;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Bungkus Storage::url() dengan penanganan error.
 *
 * Disk "cloudinary" melakukan API call sungguhan setiap kali generate URL
 * (bukan sekadar membangun string pola CDN), jadi bisa gagal kalau file
 * tidak ada di akun Cloudinary (path lama, terhapus manual, dsb) atau
 * Cloudinary API sedang bermasalah. Tanpa pembungkus ini, kegagalan itu
 * melempar exception yang meruntuhkan seluruh halaman (500) hanya karena
 * satu foto tidak bisa ditampilkan.
 */
class FileUrl
{
    public static function of(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        try {
            return Storage::url($path);
        } catch (\Throwable $e) {
            Log::warning('FileUrl: gagal generate URL, fallback ke null', [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
