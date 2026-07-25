<?php

namespace App\Support;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * Bungkus Storage::url() dengan penanganan error, dan untuk disk
 * "cloudinary" secara khusus membangun URL CDN langsung dari public_id
 * tanpa memanggil Cloudinary Admin API.
 *
 * CloudinaryStorageAdapter::getUrl() (paket cloudinary-labs/cloudinary-laravel)
 * memanggil $cloudinary->adminApi()->asset(...) setiap kali dipanggil -- itu
 * HTTP request sungguhan ke Cloudinary, bukan sekadar membangun string URL.
 * Akun Cloudinary gratis hanya punya jatah 500 panggilan Admin API/jam, jadi
 * memuat galeri dengan beberapa foto sekaligus (tiap foto = 1 panggilan)
 * cepat menghabiskan kuota itu -- setelah itu SEMUA foto gagal tampil
 * (rate limited) sampai jam berikutnya, walau file-nya baik-baik saja di
 * Cloudinary. Membangun URL langsung dari pola CDN publik Cloudinary tidak
 * memakan kuota API sama sekali.
 */
class FileUrl
{
    public static function of(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (config('filesystems.default') === 'cloudinary') {
            $direct = static::cloudinaryDirectUrl($path);
            if ($direct) {
                return $direct;
            }
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
     * Bangun URL Cloudinary langsung dari public_id, tanpa API call.
     * Mereplikasi logika CloudinaryStorageAdapter::prepareResource(): untuk
     * file gambar, public_id-nya adalah path TANPA ekstensi.
     */
    private static function cloudinaryDirectUrl(string $path): ?string
    {
        $cloudName = static::cloudinaryCloudName();

        if (! $cloudName) {
            return null;
        }

        $mimeType = (new \League\MimeTypeDetection\FinfoMimeTypeDetector)->detectMimeTypeFromPath($path);
        $resourceType = 'raw';

        if ($mimeType && str_starts_with($mimeType, 'image/')) {
            $resourceType = 'image';
        } elseif ($mimeType && str_starts_with($mimeType, 'video/')) {
            $resourceType = 'video';
        }

        $info = pathinfo($path);
        $dirname = str_replace('\\', '/', $info['dirname']);
        $publicId = ($resourceType === 'raw')
            ? $path
            : $dirname . '/' . $info['filename'];

        return "https://res.cloudinary.com/{$cloudName}/{$resourceType}/upload/{$publicId}";
    }

    private static function cloudinaryCloudName(): ?string
    {
        $url = trim((string) config('filesystems.disks.cloudinary.url', ''));

        if (! $url) {
            return null;
        }

        // Format: cloudinary://API_KEY:API_SECRET@CLOUD_NAME
        $host = parse_url($url, PHP_URL_HOST);

        return $host ?: null;
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
