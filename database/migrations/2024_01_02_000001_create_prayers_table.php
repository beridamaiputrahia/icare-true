<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prayers', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('isi_doa');
            $table->string('pengirim')->nullable();
            $table->enum('status', ['pending', 'approved', 'answered'])->default('pending');
            $table->boolean('is_anonymous')->default(false);
            $table->text('catatan_admin')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('answered_at')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prayers');
    }
};
