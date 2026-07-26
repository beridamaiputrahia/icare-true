<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    private const SYSTEM_TENANT_SLUG = 'default-branding';

    protected $fillable = [
        'nama_perusahaan',
        'slug',
        'email',
        'logo',
        'is_active',
        'is_system',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_system' => 'boolean',
        ];
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Tenant khusus (bukan I Care Group sungguhan) yang jadi wadah
     * "Pengaturan Aplikasi Default" untuk superadmin yang belum "masuk
     * sebagai" grup tertentu -- supaya perubahan nama/logo/dll di layar itu
     * tidak menimpa data grup jemaat asli manapun (termasuk grup pertama
     * "icaretrue"). Dibuat otomatis sekali kalau belum ada, tidak pernah
     * tampil di halaman registrasi atau daftar I Care Group biasa.
     */
    public static function defaultBrandingTenant(): self
    {
        return static::firstOrCreate(
            ['slug' => self::SYSTEM_TENANT_SLUG],
            [
                'nama_perusahaan' => 'I Care App (Default)',
                'is_active'       => false,
                'is_system'       => true,
            ]
        );
    }

    public function scopeVisible($query)
    {
        return $query->where('is_system', false);
    }
}
