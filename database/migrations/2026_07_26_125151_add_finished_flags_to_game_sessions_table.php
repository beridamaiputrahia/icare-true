<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Sebelumnya GameEnded langsung disiarkan begitu SATU pemain menyelesaikan
 * ronde terakhirnya, memaksa lawan yang belum selesai langsung terlempar ke
 * layar hasil di tengah permainan (terlihat seperti "macet"). Kolom ini
 * melacak siapa saja yang sudah lapor selesai, supaya GameEnded baru
 * disiarkan setelah KEDUA pemain benar-benar selesai.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game_sessions', function (Blueprint $table) {
            $table->boolean('challenger_finished')->default(false)->after('score_opponent');
            $table->boolean('opponent_finished')->default(false)->after('challenger_finished');
        });
    }

    public function down(): void
    {
        Schema::table('game_sessions', function (Blueprint $table) {
            $table->dropColumn(['challenger_finished', 'opponent_finished']);
        });
    }
};
