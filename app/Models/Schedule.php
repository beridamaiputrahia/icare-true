<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Schedule extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'nama_kegiatan',
        'tanggal',
        'jam',
        'lokasi',
        'link_maps',
        'pembicara',
        'pembicara_id',
        'deskripsi',
        'status',
        'created_by',
        'tenant_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function speaker()
    {
        return $this->belongsTo(User::class, 'pembicara_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function getFormattedDateAttribute(): string
    {
        return $this->tanggal ? $this->tanggal->format('d F Y') : '-';
    }

    public function getFormattedTimeAttribute(): string
    {
        return $this->jam ? date('H:i', strtotime($this->jam)) . ' WIB' : '-';
    }

    /**
     * URL untuk iframe embed peta. Sebelumnya embed selalu mem-geocode ulang
     * teks $lokasi ("q=" nama tempat) alih-alih memakai link_maps yang
     * user tempel sendiri — kalau nama lokasi ambigu/umum (mis. "Rumah Kak
     * Febri"), Google Maps text search bisa nyasar ke lokasi yang sama
     * sekali berbeda (bahkan luar negeri), padahal link_maps-nya sendiri
     * (tombol "Buka di Google Maps") sudah menunjuk titik yang benar.
     *
     * Di sini kita coba ekstrak koordinat presisi dari link_maps dulu (pola
     * @lat,lng atau !3dlat!4dlng yang umum di URL share Google Maps). Kalau
     * link yang ditempel adalah link PENDEK (maps.app.goo.gl / goo.gl —
     * format yang paling umum dipakai orang share dari app Google Maps di
     * HP), URL itu sendiri tidak mengandung koordinat sama sekali — harus
     * di-resolve dulu lewat redirect HTTP untuk dapat URL panjang yang
     * mengandung koordinat, baru diekstrak. Hasil resolve di-cache permanen
     * per link (link pendek Google Maps tidak pernah berubah tujuan)
     * supaya tidak menghubungi Google di setiap page load.
     */
    public function getMapsEmbedUrlAttribute(): ?string
    {
        if ($this->link_maps) {
            $target = $this->resolveShortMapsLink($this->link_maps);

            if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $target, $m)
                || preg_match('/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/', $target, $m)
                || preg_match('/[?&]q=(-?\d+\.\d+),(-?\d+\.\d+)/', $target, $m)
            ) {
                return 'https://maps.google.com/maps?q=' . $m[1] . ',' . $m[2] . '&output=embed';
            }
        }

        if ($this->lokasi) {
            return 'https://maps.google.com/maps?q=' . urlencode($this->lokasi) . '&output=embed';
        }

        return null;
    }

    /**
     * Kalau $url adalah link pendek Google Maps (goo.gl/maps.app.goo.gl),
     * ikuti redirect-nya untuk dapat URL panjang berkoordinat. Untuk link
     * lain (sudah berupa URL panjang), kembalikan apa adanya tanpa request
     * jaringan sama sekali.
     */
    private function resolveShortMapsLink(string $url): string
    {
        if (! preg_match('#^https?://(maps\.app\.)?goo\.gl/#i', $url)) {
            return $url;
        }

        return Cache::rememberForever('maps_short_link_resolved:' . md5($url), function () use ($url) {
            try {
                $response = Http::timeout(4)->withOptions(['allow_redirects' => false])->get($url);
                $location = $response->header('Location');

                return $location ?: $url;
            } catch (\Throwable $e) {
                return $url;
            }
        });
    }
}
