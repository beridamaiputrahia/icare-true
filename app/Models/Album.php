<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Album extends Model
{
    protected $fillable = [
        'user_id', 'judul', 'deskripsi', 'cover',
        'tanggal_kegiatan', 'is_published',
    ];

    protected $casts = [
        'tanggal_kegiatan' => 'date',
        'is_published'     => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class)->orderBy('sort_order');
    }

    public function getCoverUrlAttribute(): ?string
    {
        return $this->cover ? Storage::url($this->cover) : null;
    }

    public function getPhotosCountAttribute(): int
    {
        return $this->photos()->count();
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }
}
