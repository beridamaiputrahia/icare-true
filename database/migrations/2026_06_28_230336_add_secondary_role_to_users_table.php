<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Secondary role hanya berlaku untuk admin (nullable = tidak ada double-role)
            $table->enum('secondary_role', ['icl', 'ctl', 'anggota'])
                  ->nullable()
                  ->default(null)
                  ->after('role');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('secondary_role');
        });
    }
};
