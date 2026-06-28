<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('total_points')->default(0)->after('role');
            $table->unsignedTinyInteger('level')->default(1)->after('total_points');
            $table->boolean('is_online')->default(false)->after('level');
            $table->timestamp('last_seen')->nullable()->after('is_online');
            $table->string('avatar')->nullable()->after('last_seen');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['total_points', 'level', 'is_online', 'last_seen', 'avatar']);
        });
    }
};
