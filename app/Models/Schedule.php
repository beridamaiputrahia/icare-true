<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

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
}
