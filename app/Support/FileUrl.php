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

    /**
     * URL versi persegi (padded, background putih) dari sebuah gambar Cloudinary.
     *
     * PWA icon manifest mendeklarasikan dimensi persegi (192x192, 512x512) tapi
     * logo yang di-upload admin bisa berukuran apa saja. Chrome desktop menolak
     * icon yang rasionya tidak cocok dengan yang dideklarasikan dan fallback ke
     * huruf inisial, sementara Chrome Android lebih toleran -- jadi ini perlu
     * dipaksa persegi di sisi server, bukan cuma dideklarasikan begitu saja.
     */
    public static function square(?string $path, int $size): ?string
    {
        $url = static::of($path);

        if (! $url || ! str_contains($url, '/upload/')) {
            return $url;
        }

        $transform = "c_pad,b_white,w_{$size},h_{$size}";

        return preg_replace('#/upload/#', "/upload/{$transform}/", $url, 1);
    }
}
