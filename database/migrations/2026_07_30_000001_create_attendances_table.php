<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained()->cascadeOnDelete();
            $table->foreignId('member_id')->constrained()->cascadeOnDelete();
            $table->foreignId('scanned_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('scanned_at');
            $table->foreignId('tenant_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();

            $table->unique(['schedule_id', 'member_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
