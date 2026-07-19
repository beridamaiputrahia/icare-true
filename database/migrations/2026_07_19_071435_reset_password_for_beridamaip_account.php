<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

/**
 * Reset password akun beridamaip.hia@gmail.com karena SMTP belum
 * dikonfigurasi (fitur "Lupa Password" tidak bisa kirim email reset).
 * Password sementara: GantiSayaSegera123!
 * GANTI SEGERA lewat halaman Profil setelah berhasil login.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')
            ->where('email', 'beridamaip.hia@gmail.com')
            ->update(['password' => Hash::make('GantiSayaSegera123!')]);
    }

    public function down(): void
    {
        // Tidak ada cara aman mengembalikan password lama (sudah di-hash).
    }
};
