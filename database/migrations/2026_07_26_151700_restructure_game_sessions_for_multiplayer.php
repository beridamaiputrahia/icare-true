<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Ubah game_sessions dari model 1v1 tetap (challenger_id/opponent_id,
 * score_challenger/score_opponent) menjadi mendukung 2-4 pemain per sesi.
 * Peserta & skor sekarang di tabel terpisah game_session_participants
 * (satu baris per pemain per sesi), supaya jumlahnya tidak terbatas 2.
 *
 * game_sessions sendiri jadi cuma menyimpan metadata sesi: siapa host
 * (yang menantang & memilih game), tipe game, status, seed soal.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game_sessions', function (Blueprint $table) {
            $table->foreignId('host_id')->nullable()->after('code')->constrained('users')->cascadeOnDelete();
        });

        // Backfill: host = challenger lama (yang mengirim tantangan).
        DB::table('game_sessions')->update(['host_id' => DB::raw('challenger_id')]);

        Schema::table('game_sessions', function (Blueprint $table) {
            $table->foreignId('host_id')->nullable(false)->change();
        });

        Schema::create('game_session_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['invited', 'accepted', 'declined'])->default('invited');
            $table->integer('score')->default(0);
            $table->boolean('finished')->default(false);
            $table->timestamps();

            $table->unique(['game_session_id', 'user_id']);
        });

        // Backfill baris peserta dari data 1v1 lama, supaya riwayat game
        // sebelumnya (untuk hitung menang/kalah & papan peringkat) tetap utuh.
        $oldSessions = DB::table('game_sessions')->get([
            'id', 'challenger_id', 'opponent_id', 'score_challenger', 'score_opponent',
            'status', 'challenger_finished', 'opponent_finished',
        ]);

        $rows = [];
        $now  = now();
        foreach ($oldSessions as $s) {
            $rows[] = [
                'game_session_id' => $s->id,
                'user_id'         => $s->challenger_id,
                'status'          => 'accepted',
                'score'           => $s->score_challenger,
                'finished'        => (bool) $s->challenger_finished,
                'created_at'      => $now,
                'updated_at'      => $now,
            ];
            if ($s->opponent_id) {
                $rows[] = [
                    'game_session_id' => $s->id,
                    'user_id'         => $s->opponent_id,
                    'status'          => in_array($s->status, ['active', 'finished']) ? 'accepted' : ($s->status === 'declined' ? 'declined' : 'invited'),
                    'score'           => $s->score_opponent,
                    'finished'        => (bool) $s->opponent_finished,
                    'created_at'      => $now,
                    'updated_at'      => $now,
                ];
            }
        }
        foreach (array_chunk($rows, 500) as $chunk) {
            DB::table('game_session_participants')->insert($chunk);
        }

        Schema::table('game_sessions', function (Blueprint $table) {
            $table->dropForeign(['challenger_id']);
            $table->dropForeign(['opponent_id']);
            $table->dropColumn([
                'challenger_id', 'opponent_id',
                'score_challenger', 'score_opponent',
                'challenger_finished', 'opponent_finished',
            ]);
        });
    }

    public function down(): void
    {
        Schema::table('game_sessions', function (Blueprint $table) {
            $table->foreignId('challenger_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('opponent_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->integer('score_challenger')->default(0);
            $table->integer('score_opponent')->default(0);
            $table->boolean('challenger_finished')->default(false);
            $table->boolean('opponent_finished')->default(false);
        });

        // Backfill terbalik hanya untuk sesi 2 pemain (host + 1 peserta lain);
        // sesi dengan >2 peserta tidak bisa direpresentasikan lagi di skema lama.
        $sessions = DB::table('game_sessions')->get(['id', 'host_id']);
        foreach ($sessions as $s) {
            $participants = DB::table('game_session_participants')
                ->where('game_session_id', $s->id)
                ->get();

            $host  = $participants->firstWhere('user_id', $s->host_id);
            $other = $participants->firstWhere('user_id', '!=', $s->host_id);

            DB::table('game_sessions')->where('id', $s->id)->update([
                'challenger_id'        => $s->host_id,
                'opponent_id'          => $other->user_id ?? null,
                'score_challenger'     => $host->score ?? 0,
                'score_opponent'       => $other->score ?? 0,
                'challenger_finished'  => $host->finished ?? false,
                'opponent_finished'    => $other->finished ?? false,
            ]);
        }

        Schema::dropIfExists('game_session_participants');

        Schema::table('game_sessions', function (Blueprint $table) {
            $table->dropForeign(['host_id']);
            $table->dropColumn('host_id');
        });
    }
};
