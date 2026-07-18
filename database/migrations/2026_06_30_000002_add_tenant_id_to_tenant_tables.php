<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Tambahkan tenant_id ke semua tabel milik tenant.
 * Tabel yang TIDAK dapat tenant_id: tenants, users (ditangani migration terpisah),
 * cache, cache_locks, jobs, job_batches, failed_jobs, notifications (sistem).
 *
 * Urutan: tabel tanpa FK dulu, baru tabel dengan FK ke tabel lain agar aman.
 */
return new class extends Migration
{
    /** Tabel-tabel data milik tenant beserta nullable-nya */
    private array $tables = [
        // tabel utama konten komunitas
        'schedules',
        'announcements',
        'members',
        'devotions',
        'daily_verses',
        'prayers',
        'achievements',
        'banners',
        'app_settings',
        // fase 4 – poin, galeri, chat, game
        'user_points',
        'albums',
        'photos',
        'conversations',
        'conversation_participants',
        'messages',
        'message_reads',
        'game_sessions',
    ];

    public function up(): void
    {
        // ── users ── ditangani di sini juga agar FK ke tenants sudah ada
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('tenant_id')
                  ->nullable()          // nullable agar data lama tidak rusak
                  ->after('id')
                  ->constrained('tenants')
                  ->cascadeOnDelete();
        });

        foreach ($this->tables as $tableName) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $table->foreignId('tenant_id')
                      ->nullable()
                      ->after('id')
                      ->constrained('tenants')
                      ->cascadeOnDelete();

                // Index agar WHERE tenant_id = ? cepat
                $table->index('tenant_id', "idx_{$tableName}_tenant");
            });
        }
    }

    public function down(): void
    {
        // Hapus FK & kolom dari users dulu
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });

        foreach (array_reverse($this->tables) as $tableName) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                $table->dropIndex("idx_{$tableName}_tenant");
                $table->dropForeign(['tenant_id']);
                $table->dropColumn('tenant_id');
            });
        }
    }
};
