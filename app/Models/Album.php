<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Album extends Model
{
    use BelongsToTenant;
    protected $fillable = [
        'user_id', 'judul', 'deskripsi', 'cover',
        'tanggal_kegiatan', 'is_published',
        'tenant_id',
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
        return \App\Support\FileUrl::of($this->cover);
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
