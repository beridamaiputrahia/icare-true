<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Bank soal tambahan yang bisa dikelola superadmin lewat halaman admin, di
 * luar bank soal bawaan (hardcoded) di GameFeature.jsx. Global lintas tenant
 * (bukan per I Care Group) karena tujuannya menambah variasi soal untuk
 * semua pengguna aplikasi, bukan konten khusus satu grup.
 *
 * Struktur kolom `data` berbeda-beda tergantung game_type (lihat model
 * GameQuestion):
 *  - kuis:   {q, opsi: string[4], benar: int}       -- Kuis Adu Cepat
 *  - susun:  {ref, teks}                             -- Susun Ayat
 *  - tebak:  {jawaban, clues: string[3], salah: string[3]} -- Tebak Tokoh
 *  - memory: {a, b}                                   -- Memory Match
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_questions', function (Blueprint $table) {
            $table->id();
            $table->enum('game_type', ['kuis', 'susun', 'tebak', 'memory']);
            $table->json('data');
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['game_type', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_questions');
    }
};
