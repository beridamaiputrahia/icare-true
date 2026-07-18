<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Bug lama di RegisteredUserController membuat setiap pendaftar publik
 * jadi admin di tenant baru sendiri (nama diawali "Komunitas ..."), alih-alih
 * bergabung ke tenant utama sebagai anggota biasa. Pindahkan semua user yang
 * kena bug ini ke tenant utama ("icaretrue"), ubah role jadi anggota, lalu
 * hapus tenant kosong yang tersisa.
 */
return new class extends Migration
{
    public function up(): void
    {
        $mainTenant = DB::table('tenants')->where('slug', 'icaretrue')->first();

        if (! $mainTenant) {
            return;
        }

        $buggyTenants = DB::table('tenants')
            ->where('nama_perusahaan', 'like', 'Komunitas %')
            ->where('slug', '!=', 'icaretrue')
            ->pluck('id');

        if ($buggyTenants->isEmpty()) {
            return;
        }

        DB::table('users')
            ->whereIn('tenant_id', $buggyTenants)
            ->update([
                'tenant_id' => $mainTenant->id,
                'role' => 'anggota',
            ]);

        DB::table('members')
            ->whereIn('tenant_id', $buggyTenants)
            ->update(['tenant_id' => $mainTenant->id]);

        DB::table('app_settings')->whereIn('tenant_id', $buggyTenants)->delete();

        DB::table('tenants')->whereIn('id', $buggyTenants)->delete();
    }

    public function down(): void
    {
        // Tidak ada cara aman mengembalikan tenant asli yang sudah dihapus.
    }
};
