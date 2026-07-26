<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            // Tenant khusus wadah "Pengaturan Aplikasi Default" superadmin --
            // bukan I Care Group sungguhan yang dipakai jemaat, jadi disembunyikan
            // dari daftar/registrasi publik dan tidak dihitung sebagai grup biasa.
            $table->boolean('is_system')->default(false)->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn('is_system');
        });
    }
};
