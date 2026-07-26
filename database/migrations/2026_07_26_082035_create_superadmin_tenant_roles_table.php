<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    /**
     * Identitas/label peran seorang superadmin di dalam satu I Care Group
     * tertentu (mis. tampil sebagai "ICL" di grup A, "Anggota" di grup B),
     * TANPA mengubah role global superadmin di kolom users.role dan TANPA
     * membatasi akses -- ini murni label/identitas untuk ditampilkan,
     * superadmin tetap punya akses penuh di mana pun.
     */
    public function up(): void
    {
        Schema::create('superadmin_tenant_roles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->string('role'); // anggota|icl|ctl|admin
            $table->timestamps();

            $table->unique(['user_id', 'tenant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('superadmin_tenant_roles');
    }
};
