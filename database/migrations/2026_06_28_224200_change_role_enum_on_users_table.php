<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            // Postgres tidak punya ENUM inline/MODIFY COLUMN; gunakan check constraint via text.
            DB::statement("ALTER TABLE users ALTER COLUMN role DROP DEFAULT");
            DB::statement("ALTER TABLE users ALTER COLUMN role TYPE VARCHAR(255)");
            DB::table('users')->where('role', 'user')->update(['role' => 'anggota']);
            DB::statement("ALTER TABLE users ALTER COLUMN role SET DEFAULT 'anggota'");
            DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('admin','icl','ctl','anggota'))");

            return;
        }

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

        if (DB::getDriverName() === 'pgsql') {
            DB::statement("ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check");
            DB::statement("ALTER TABLE users ALTER COLUMN role DROP DEFAULT");
            DB::statement("ALTER TABLE users ALTER COLUMN role SET DEFAULT 'user'");
            DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('admin','user'))");

            return;
        }

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','user') NOT NULL DEFAULT 'user'");
    }
};
