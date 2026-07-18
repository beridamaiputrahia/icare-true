<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\User;

/**
 * Layanan untuk mengecek apakah sebuah fitur CRUD diaktifkan
 * dan apakah user tertentu berhak mengaksesnya.
 *
 * Aturan:
 *  - Admin selalu punya akses penuh (tidak perlu toggle).
 *  - ICL / CTL hanya bisa akses jika toggle fitur = ON.
 *  - Admin dengan secondary_role juga dapat akses fitur yang sesuai secondary-nya.
 */
class FeatureToggleService
{
    // Pemetaan nama fitur → key di app_settings
    const FEATURES = [
        'jadwal'       => 'feat_crud_jadwal',
        'pengumuman'   => 'feat_crud_pengumuman',
        'anggota'      => 'feat_crud_anggota',
        'renungan'     => 'feat_crud_renungan',
        'doa'          => 'feat_crud_doa',
        'ayat_harian'  => 'feat_crud_ayat_harian',
    ];

    /** Apakah sebuah fitur diaktifkan admin (setting = '1') */
    public function isEnabled(string $feature): bool
    {
        $key = self::FEATURES[$feature] ?? null;
        if (! $key) return false;
        return AppSetting::get($key, '0') === '1';
    }

    /**
     * Apakah user berhak mengakses fitur CRUD tertentu.
     *
     * Admin → selalu true.
     * ICL/CTL → true hanya jika toggle fitur ON.
     * Admin + secondary_role (ICL/CTL) → true selalu (admin tetap admin).
     * Anggota → selalu false.
     */
    public function userCan(User $user, string $feature): bool
    {
        // Admin (termasuk superadmin) selalu bisa
        if ($user->isAdmin()) return true;

        // ICL/CTL: cek toggle
        if ($user->isICL() || $user->isCTL()) {
            return $this->isEnabled($feature);
        }

        return false;
    }

    /**
     * Ambil semua status fitur sekaligus (untuk blade / JS).
     * Return: ['jadwal' => true, 'pengumuman' => false, ...]
     */
    public function allStatuses(): array
    {
        return array_map(
            fn($key) => AppSetting::get($key, '0') === '1',
            self::FEATURES
        );
    }

    /**
     * Ambil semua fitur beserta status untuk user tertentu.
     * Return: ['jadwal' => true, 'pengumuman' => false, ...]
     */
    public function userPermissions(User $user): array
    {
        if ($user->isAdmin()) {
            return array_fill_keys(array_keys(self::FEATURES), true);
        }

        return array_map(
            fn($feature) => $this->userCan($user, $feature),
            array_combine(array_keys(self::FEATURES), array_keys(self::FEATURES))
        );
    }
}
