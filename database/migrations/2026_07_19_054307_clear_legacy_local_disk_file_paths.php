<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Semua file yang di-upload sebelum aplikasi pindah ke disk Cloudinary
 * (Render tidak punya persistent disk) sudah hilang dari filesystem lokal.
 * Path lama di database tetap merujuk ke file yang tidak ada di Cloudinary,
 * dan Storage::url() untuk disk cloudinary melakukan API call sungguhan
 * yang gagal untuk path semacam itu, menyebabkan error 500. Kosongkan
 * semua kolom path file lama supaya UI fallback ke placeholder sampai
 * user upload ulang.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('members')->whereNotNull('foto')->update(['foto' => null]);
        DB::table('users')->whereNotNull('avatar')->update(['avatar' => null]);
        DB::table('announcements')->whereNotNull('gambar')->update(['gambar' => null]);
        DB::table('devotions')->whereNotNull('gambar')->update(['gambar' => null]);
        DB::table('albums')->whereNotNull('cover')->update(['cover' => null]);
        DB::table('tenants')->whereNotNull('logo')->update(['logo' => null]);

        // photos.file_path tidak nullable (kolom wajib) — hapus baris lama
        // sekaligus, karena filenya sudah tidak ada dan tidak bisa "dikosongkan".
        DB::table('photos')->delete();

        DB::table('app_settings')
            ->whereIn('key', ['logo', 'favicon', 'hero_banner'])
            ->update(['value' => null]);
    }

    public function down(): void
    {
        // Tidak ada data lama untuk dikembalikan — file fisiknya sudah hilang.
    }
};
