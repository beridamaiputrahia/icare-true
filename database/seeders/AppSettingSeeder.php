<?php

namespace Database\Seeders;

use App\Models\AppSetting;
use Illuminate\Database\Seeder;

/**
 * Buat setting default untuk satu tenant.
 * Dipanggil dengan: $this->call(AppSettingSeeder::class, false, ['tenantId' => $tenant->id])
 */
class AppSettingSeeder extends Seeder
{
    public function run(int $tenantId, string $appName = 'I Care True'): void
    {
        $defaults = [
            // ── General ────────────────────────────────────────────────
            ['key' => 'app_name',         'value' => $appName,             'type' => 'text',    'label' => 'Nama Aplikasi',        'group' => 'general',     'sort_order' => 1],
            ['key' => 'app_description',  'value' => '',                   'type' => 'textarea','label' => 'Deskripsi Singkat',    'group' => 'general',     'sort_order' => 2],
            // ── Appearance ─────────────────────────────────────────────
            ['key' => 'logo',             'value' => null,                 'type' => 'image',   'label' => 'Logo',                 'group' => 'appearance',  'sort_order' => 1],
            ['key' => 'favicon',          'value' => null,                 'type' => 'image',   'label' => 'Favicon',              'group' => 'appearance',  'sort_order' => 2],
            ['key' => 'hero_banner',      'value' => null,                 'type' => 'image',   'label' => 'Hero Banner',          'group' => 'appearance',  'sort_order' => 3],
            ['key' => 'primary_color',    'value' => '#2563eb',            'type' => 'color',   'label' => 'Warna Utama',          'group' => 'appearance',  'sort_order' => 4],
            ['key' => 'secondary_color',  'value' => '#1d4ed8',            'type' => 'color',   'label' => 'Warna Sekunder',       'group' => 'appearance',  'sort_order' => 5],
            ['key' => 'sidebar_color',    'value' => '#1e293b',            'type' => 'color',   'label' => 'Warna Sidebar',        'group' => 'appearance',  'sort_order' => 6],
            // ── Contact ────────────────────────────────────────────────
            ['key' => 'wa_leader',        'value' => '',                   'type' => 'text',    'label' => 'WhatsApp Leader',      'group' => 'contact',     'sort_order' => 1],
            ['key' => 'wa_co_leader',     'value' => '',                   'type' => 'text',    'label' => 'WhatsApp Co-Leader',   'group' => 'contact',     'sort_order' => 2],
            // ── Maintenance ────────────────────────────────────────────
            ['key' => 'maintenance_mode', 'value' => '0',                  'type' => 'boolean', 'label' => 'Mode Maintenance',     'group' => 'maintenance', 'sort_order' => 1],
            ['key' => 'maintenance_msg',  'value' => 'Sistem sedang dalam pemeliharaan.', 'type' => 'textarea', 'label' => 'Pesan Maintenance', 'group' => 'maintenance', 'sort_order' => 2],
            ['key' => 'maintenance_eta',  'value' => '',                   'type' => 'text',    'label' => 'Estimasi Selesai',     'group' => 'maintenance', 'sort_order' => 3],
            // ── Feature Toggles ────────────────────────────────────────
            ['key' => 'feat_crud_jadwal',      'value' => '0', 'type' => 'boolean', 'label' => 'CRUD Jadwal (ICL & CTL)',               'group' => 'features', 'sort_order' => 1],
            ['key' => 'feat_crud_pengumuman',  'value' => '0', 'type' => 'boolean', 'label' => 'CRUD Pengumuman (ICL & CTL)',           'group' => 'features', 'sort_order' => 2],
            ['key' => 'feat_crud_anggota',     'value' => '0', 'type' => 'boolean', 'label' => 'CRUD Anggota (ICL & CTL)',              'group' => 'features', 'sort_order' => 3],
            ['key' => 'feat_crud_renungan',    'value' => '0', 'type' => 'boolean', 'label' => 'Approve/Reject Renungan (ICL & CTL)',   'group' => 'features', 'sort_order' => 4],
            ['key' => 'feat_crud_doa',         'value' => '0', 'type' => 'boolean', 'label' => 'Approve/Tandai Doa (ICL & CTL)',        'group' => 'features', 'sort_order' => 5],
            ['key' => 'feat_crud_ayat_harian', 'value' => '0', 'type' => 'boolean', 'label' => 'CRUD Ayat Harian (ICL & CTL)',          'group' => 'features', 'sort_order' => 6],
        ];

        foreach ($defaults as $row) {
            AppSetting::withoutTenantScope()->updateOrCreate(
                ['key' => $row['key'], 'tenant_id' => $tenantId],
                array_merge($row, ['tenant_id' => $tenantId])
            );
        }
    }
}
