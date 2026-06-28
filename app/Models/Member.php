<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'foto',
        'nama_lengkap',
        'nama_panggilan',
        'nomor_hp',
        'alamat',
        'tanggal_lahir',
        'covenant_number',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getAgeAttribute(): int
    {
        return $this->tanggal_lahir ? $this->tanggal_lahir->age : 0;
    }

    public function getBirthdayThisMonthAttribute(): bool
    {
        return $this->tanggal_lahir && $this->tanggal_lahir->month === now()->month;
    }
}
