<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Tambah status 'left' pada game_session_participants — dipakai saat pemain
 * sengaja keluar (tombol Keluar / tutup tab) di tengah sesi 'active', supaya
 * pemain lain bisa diberi tahu dan memilih lanjut tanpa dia atau mengakhiri
 * sesi. Laravel enum() di Postgres jadi CHECK constraint, bukan native enum
 * type, jadi harus drop+recreate constraint-nya untuk menambah nilai baru.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE game_session_participants DROP CONSTRAINT IF EXISTS game_session_participants_status_check');
            DB::statement("ALTER TABLE game_session_participants ADD CONSTRAINT game_session_participants_status_check CHECK (status IN ('invited','accepted','declined','left'))");
        } else {
            DB::statement("ALTER TABLE game_session_participants MODIFY COLUMN status ENUM('invited','accepted','declined','left') NOT NULL DEFAULT 'invited'");
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE game_session_participants DROP CONSTRAINT IF EXISTS game_session_participants_status_check');
            DB::statement("ALTER TABLE game_session_participants ADD CONSTRAINT game_session_participants_status_check CHECK (status IN ('invited','accepted','declined'))");
        } else {
            DB::statement("ALTER TABLE game_session_participants MODIFY COLUMN status ENUM('invited','accepted','declined') NOT NULL DEFAULT 'invited'");
        }
    }
};
