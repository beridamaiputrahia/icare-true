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

    /**
     * Ayat aktif hari ini untuk tenant TERTENTU (dipakai command scheduler
     * yang tidak punya user login untuk resolve tenant via BelongsToTenant
     * scope) — atau tenant dari user yang sedang login kalau $tenantId
     * dikosongkan (perilaku lama, dipakai controller/middleware).
     */
    public static function getToday(?int $tenantId = null)
    {
        $query = $tenantId !== null ? static::withoutTenantScope()->where('tenant_id', $tenantId) : static::query();

        return (clone $query)->where('tanggal', today())->where('is_active', true)->first()
            ?? (clone $query)->where('is_active', true)->latest()->first();
    }
}
