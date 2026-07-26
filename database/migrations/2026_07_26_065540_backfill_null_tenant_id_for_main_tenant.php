<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Migration 2026_06_30_000002_add_tenant_id_to_tenant_tables menambahkan
 * kolom tenant_id sebagai nullable ke semua tabel data komunitas, tapi tidak
 * pernah backfill baris yang sudah ada sebelum migrasi itu dijalankan --
 * baris-baris itu tertinggal dengan tenant_id = NULL selamanya.
 *
 * Karena BelongsToTenant::bootBelongsToTenant() memfilter query dengan
 * WHERE tenant_id = <tenant aktif> (perbandingan strict, NULL tidak pernah
 * cocok), baris ber-tenant_id NULL jadi tidak pernah terlihat/ter-update
 * oleh siapa pun -- termasuk Pengaturan Aplikasi tenant utama yang jadi
 * tampak "tidak bisa disimpan" (foreach loop di controller cuma dapat 0
 * baris, jadi tidak ada yang di-update, tapi tetap redirect sukses).
 *
 * Tenant utama tempat data lama ini seharusnya berada adalah tenant dengan
 * slug "icaretrue" (dibuat pertama kali oleh UserSeeder sebelum sistem
 * multi-tenant ada), mengikuti pola yang sama dengan migration
 * 2026_07_19_061631_fix_self_registered_users_wrong_tenant.
 */
return new class extends Migration
{
    private array $tables = [
        'schedules', 'announcements', 'members', 'devotions', 'daily_verses',
        'prayers', 'achievements', 'banners', 'app_settings',
        'user_points', 'albums', 'photos', 'conversations',
        'conversation_participants', 'messages', 'message_reads', 'game_sessions',
    ];

    public function up(): void
    {
        $mainTenant = DB::table('tenants')->where('slug', 'icaretrue')->first();

        if (! $mainTenant) {
            return;
        }

        foreach ($this->tables as $tableName) {
            if (! Schema::hasTable($tableName) || ! Schema::hasColumn($tableName, 'tenant_id')) {
                continue;
            }

            // app_settings punya unique constraint gabungan (key, tenant_id) --
            // kalau tenant utama SUDAH punya baris untuk key tertentu (mis. dari
            // backfill/seeder manual sebelumnya), baris NULL dengan key yang sama
            // adalah duplikat usang: hapus, jangan di-update (akan melanggar
            // constraint dan menggagalkan migrasi/deploy).
            if ($tableName === 'app_settings') {
                $existingKeys = DB::table('app_settings')
                    ->where('tenant_id', $mainTenant->id)
                    ->pluck('key');

                DB::table('app_settings')
                    ->whereNull('tenant_id')
                    ->whereIn('key', $existingKeys)
                    ->delete();
            }

            DB::table($tableName)
                ->whereNull('tenant_id')
                ->update(['tenant_id' => $mainTenant->id]);
        }
    }

    /**
     * Tidak ada cara aman mengembalikan baris ke tenant_id NULL -- kita tidak
     * tahu lagi baris mana yang tadinya NULL vs yang memang sudah benar
     * menunjuk ke tenant utama sejak awal.
     */
    public function down(): void
    {
        //
    }
};
