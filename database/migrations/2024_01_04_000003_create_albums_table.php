<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->string('cover')->nullable();
            $table->date('tanggal_kegiatan')->nullable();
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index('is_published');
            $table->index('tanggal_kegiatan');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('albums');
    }
};
