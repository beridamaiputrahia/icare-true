<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check');
            DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('admin','icl','ctl','anggota','superadmin'))");

            return;
        }

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','icl','ctl','anggota','superadmin') NOT NULL DEFAULT 'anggota'");
    }

    public function down(): void
    {
        DB::table('users')->where('role', 'superadmin')->update(['role' => 'admin']);

        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE users DROP CONSTRAINT IF EXISTS users_role_check');
            DB::statement("ALTER TABLE users ADD CONSTRAINT users_role_check CHECK (role IN ('admin','icl','ctl','anggota'))");

            return;
        }

        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','icl','ctl','anggota') NOT NULL DEFAULT 'anggota'");
    }
};
