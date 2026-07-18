<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Langkah 1: Perluas enum ke semua nilai lama + baru dulu (tanpa hapus 'user')
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','user','icl','ctl','anggota') NOT NULL DEFAULT 'anggota'");

        // Langkah 2: Migrate data lama 'user' -> 'anggota'
        DB::table('users')->where('role', 'user')->update(['role' => 'anggota']);

        // Langkah 3: Hapus nilai 'user' dari enum
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','icl','ctl','anggota') NOT NULL DEFAULT 'anggota'");
    }

    public function down(): void
    {
        // Kembalikan anggota/icl/ctl -> 'user' sebelum revert enum
        DB::table('users')->whereIn('role', ['anggota', 'icl', 'ctl'])->update(['role' => 'user']);

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','user') NOT NULL DEFAULT 'user'");
    }
};
