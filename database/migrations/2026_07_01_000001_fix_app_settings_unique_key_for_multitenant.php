<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('app_settings', function (Blueprint $table) {
            // Hapus unique constraint lama yang hanya pada 'key'
            $table->dropUnique(['key']);

            // Ganti dengan composite unique (key, tenant_id)
            // Pakai unique yang nullable-safe: dua row boleh sama key jika tenant_id berbeda
            $table->unique(['key', 'tenant_id'], 'app_settings_key_tenant_unique');
        });
    }

    public function down(): void
    {
        Schema::table('app_settings', function (Blueprint $table) {
            $table->dropUnique('app_settings_key_tenant_unique');
            $table->unique('key');
        });
    }
};
