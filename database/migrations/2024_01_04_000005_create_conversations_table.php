<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('conversations', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['global', 'private', 'leader'])->default('private');
            $table->string('name')->nullable(); // for global/leader channels
            $table->timestamps();
        });

        // Seed global & leader conversations
        DB::table('conversations')->insert([
            ['type' => 'global',  'name' => 'Chat Komunitas', 'created_at' => now(), 'updated_at' => now()],
            ['type' => 'leader',  'name' => 'Chat Leader',    'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('conversations');
    }
};
