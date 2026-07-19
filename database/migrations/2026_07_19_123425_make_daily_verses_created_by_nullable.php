<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Ayat harian yang di-generate otomatis (verse:generate-daily) tidak punya
 * "pembuat" manusia, jadi created_by perlu boleh null.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::statement('ALTER TABLE daily_verses ALTER COLUMN created_by DROP NOT NULL');

            return;
        }

        Schema::table('daily_verses', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable()->change();
        });
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'pgsql') {
            DB::table('daily_verses')->whereNull('created_by')->delete();
            DB::statement('ALTER TABLE daily_verses ALTER COLUMN created_by SET NOT NULL');

            return;
        }

        DB::table('daily_verses')->whereNull('created_by')->delete();

        Schema::table('daily_verses', function (Blueprint $table) {
            $table->foreignId('created_by')->nullable(false)->change();
        });
    }
};
