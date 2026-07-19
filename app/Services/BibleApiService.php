<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

/**
 * Integrasi ke API.Bible (scripture.api.bible) untuk ambil teks ayat
 * Alkitab Terjemahan Baru (TB) Bahasa Indonesia secara live.
 *
 * bibleId TB tidak di-hardcode karena UUID-nya spesifik per API key/lisensi
 * — dideteksi otomatis sekali lewat endpoint /bibles?language=ind, lalu
 * di-cache permanen (jarang berubah).
 */
class BibleApiService
{
    private const BASE_URL = 'https://api.scripture.api.bible/v1';

    public function __construct(private readonly ?string $apiKey = null)
    {
        $this->apiKey = $apiKey ?? config('services.bible_api.key');
    }

    /**
     * Ambil satu ayat acak dari daftar referensi populer (config/bible-verses.php),
     * dengan teks live dari API.Bible. Melempar exception kalau API key belum
     * di-set atau request gagal — pemanggil (Artisan command) bertanggung jawab
     * menangani fallback.
     */
    public function randomVerse(): array
    {
        if (! $this->apiKey) {
            throw new RuntimeException('API_BIBLE_KEY belum diset di environment.');
        }

        $verses = config('bible-verses.verses', []);

        if (empty($verses)) {
            throw new RuntimeException('Daftar referensi ayat (config/bible-verses.php) kosong.');
        }

        $pick = $verses[array_rand($verses)];

        $text = $this->fetchVerseText($pick['id']);

        return [
            'ayat'      => $text,
            'referensi' => $pick['label'],
        ];
    }

    /**
     * Ambil teks ayat berdasarkan ID USFM (mis. "JHN.3.16") dari Bible TB.
     */
    public function fetchVerseText(string $verseId): string
    {
        $bibleId = $this->resolveIndonesianBibleId();

        $response = Http::withHeaders(['api-key' => $this->apiKey])
            ->get(self::BASE_URL . "/bibles/{$bibleId}/verses/{$verseId}", [
                'content-type'            => 'text',
                'include-verse-numbers'   => 'false',
                'include-notes'           => 'false',
                'include-titles'          => 'false',
                'include-chapter-numbers' => 'false',
            ]);

        if (! $response->successful()) {
            Log::warning('BibleApiService: gagal ambil teks ayat', [
                'verse_id' => $verseId,
                'status'   => $response->status(),
                'body'     => $response->body(),
            ]);

            throw new RuntimeException("Gagal mengambil ayat {$verseId} dari API.Bible (HTTP {$response->status()}).");
        }

        $content = trim((string) ($response->json('data.content') ?? ''));

        if ($content === '') {
            throw new RuntimeException("Teks ayat {$verseId} kosong dari API.Bible.");
        }

        return $content;
    }

    /**
     * Cari & cache ID Alkitab Terjemahan Baru (TB) Bahasa Indonesia.
     * Prioritas: abbreviation mengandung "TB", fallback ke Bible ind pertama.
     */
    private function resolveIndonesianBibleId(): string
    {
        return Cache::rememberForever('bible_api_tb_bible_id', function () {
            $response = Http::withHeaders(['api-key' => $this->apiKey])
                ->get(self::BASE_URL . '/bibles', ['language' => 'ind']);

            if (! $response->successful()) {
                throw new RuntimeException(
                    "Gagal mengambil daftar Alkitab Bahasa Indonesia dari API.Bible (HTTP {$response->status()})."
                );
            }

            $bibles = $response->json('data', []);

            if (empty($bibles)) {
                throw new RuntimeException('Tidak ada Alkitab Bahasa Indonesia (ind) yang tersedia di akun API.Bible ini.');
            }

            $tb = collect($bibles)->first(
                fn ($b) => str_contains(strtoupper($b['abbreviation'] ?? ''), 'TB')
            );

            return $tb['id'] ?? $bibles[0]['id'];
        });
    }
}
