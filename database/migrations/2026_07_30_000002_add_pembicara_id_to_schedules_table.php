<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->foreignId('pembicara_id')->nullable()->after('pembicara')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropConstrainedForeignId('pembicara_id');
        });
    }
};
