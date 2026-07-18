<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Buat akun superadmin pertama untuk deployment yang sudah live (data lama,
 * auto-seed di entrypoint.sh tidak jalan lagi karena tabel users sudah terisi).
 * GANTI password default ini segera setelah login pertama kali.
 */
return new class extends Migration
{
    public function up(): void
    {
        $exists = DB::table('users')->where('email', 'superadmin@icaretrue.com')->exists();

        if ($exists) {
            return;
        }

        DB::table('users')->insert([
            'name'       => 'Super Administrator',
            'email'      => 'superadmin@icaretrue.com',
            'password'   => Hash::make('password'),
            'role'       => 'superadmin',
            'is_active'  => true,
            'tenant_id'  => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('users')->where('email', 'superadmin@icaretrue.com')->delete();
    }
};
