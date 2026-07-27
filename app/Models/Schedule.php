<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
     * Di sini kita coba ekstrak koordinat presisi dari link_maps dulu
     * (pola @lat,lng atau !3dlat!4dlng yang umum di URL share Google Maps),
     * baru fallback ke text search kalau linknya tidak mengandung koordinat.
     */
    public function getMapsEmbedUrlAttribute(): ?string
    {
        if ($this->link_maps) {
            if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $this->link_maps, $m)
                || preg_match('/!3d(-?\d+\.\d+)!4d(-?\d+\.\d+)/', $this->link_maps, $m)
                || preg_match('/[?&]q=(-?\d+\.\d+),(-?\d+\.\d+)/', $this->link_maps, $m)
            ) {
                return 'https://maps.google.com/maps?q=' . $m[1] . ',' . $m[2] . '&output=embed';
            }
        }

        if ($this->lokasi) {
            return 'https://maps.google.com/maps?q=' . urlencode($this->lokasi) . '&output=embed';
        }

        return null;
    }
}
