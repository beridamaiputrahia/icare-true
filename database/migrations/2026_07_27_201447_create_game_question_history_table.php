<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Riwayat soal yang baru dipakai per tenant, untuk anti-pengulangan pada
 * mode ONLINE. Beda dari anti-repeat mode Solo/Tatap (yang pakai localStorage
 * per-HP): mode online butuh daftar "soal yang harus dihindari" yang SAMA
 * untuk semua pemain di satu sesi (supaya soal yang dikirim tetap identik ke
 * semua device via seeded shuffle), jadi harus disimpan di server, per
 * tenant (I Care Group) — bukan per user atau per device.
 *
 * question_key adalah identifier stabil per soal: untuk bank bawaan
 * (hardcoded di GameFeature.jsx) berupa teks soal itu sendiri (mis. isi
 * `q`/`ref`/`jawaban`/`a`), untuk soal custom superadmin berupa
 * "custom:{id}" dari tabel game_questions. Lihat GameSessionController.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_question_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->enum('game_type', ['kuis', 'susun', 'tebak', 'memory']);
            $table->string('question_key');
            $table->timestamp('used_at');

            $table->index(['tenant_id', 'game_type', 'used_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_question_history');
    }
};
