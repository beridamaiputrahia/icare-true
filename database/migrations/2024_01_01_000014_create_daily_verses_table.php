<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('daily_verses', function (Blueprint $table) {
            $table->id();
            $table->text('ayat');
            $table->string('referensi');
            $table->text('renungan_singkat')->nullable();
            $table->date('tanggal')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('daily_verses');
    }
};
