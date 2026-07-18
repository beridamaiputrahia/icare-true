<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyVerse extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'ayat',
        'referensi',
        'renungan_singkat',
        'tanggal',
        'is_active',
        'created_by',
        'tenant_id',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'is_active' => 'boolean',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public static function getToday()
    {
        return static::where('tanggal', today())
            ->where('is_active', true)
            ->first()
            ?? static::where('is_active', true)->latest()->first();
    }
}
