<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('user_achievements', function (Blueprint $table) {
            $table->foreignId('tenant_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('tenants')
                  ->cascadeOnDelete();

            $table->index('tenant_id', 'idx_user_achievements_tenant');
        });

        // Backfill tenant_id dari relasi user_id -> users.tenant_id
        DB::table('users')->select('id', 'tenant_id')->whereNotNull('tenant_id')
            ->orderBy('id')->chunk(200, function ($users) {
                foreach ($users as $user) {
                    DB::table('user_achievements')
                        ->where('user_id', $user->id)
                        ->update(['tenant_id' => $user->tenant_id]);
                }
            });
    }

    public function down(): void
    {
        Schema::table('user_achievements', function (Blueprint $table) {
            $table->dropIndex('idx_user_achievements_tenant');
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });
    }
};
