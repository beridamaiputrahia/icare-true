<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('label');
            $table->string('description')->nullable();
            $table->string('icon')->default('fa-shield');
            $table->string('color_from')->default('#2563eb');
            $table->string('color_to')->default('#7c3aed');
            $table->string('text_color')->default('#ffffff');
            $table->enum('type', ['role', 'achievement', 'member_status'])->default('achievement');
            $table->string('condition_key')->nullable();
            $table->integer('priority')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Track user activity for statistics
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('type');
            $table->string('description');
            $table->morphs('subject');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('banners');
    }
};
