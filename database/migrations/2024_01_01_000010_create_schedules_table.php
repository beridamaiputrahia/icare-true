<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan');
            $table->date('tanggal');
            $table->time('jam');
            $table->string('lokasi');
            $table->string('link_maps')->nullable();
            $table->string('pembicara')->nullable();
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['upcoming', 'ongoing', 'done'])->default('upcoming');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
