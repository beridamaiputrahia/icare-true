<?php

namespace App\Http\Controllers;

use App\Models\GameQuestion;
use App\Models\GameSession;
use App\Models\GameSessionParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function index()
    {
        $user    = Auth::user();
        $users   = $this->otherUsers($user);
        $members = $this->buildMembers($user, $users);

        $leaderboard = $this->buildWeeklyLeaderboard($user, $users);
        $customQuestions = $this->buildCustomQuestions();

        return view('game.index', compact('user', 'members', 'leaderboard', 'customQuestions'));
    }

    /**
     * Soal tambahan dari bank soal superadmin (game_questions), dikelompokkan
     * per game_type supaya frontend tinggal menggabungkannya dengan bank
     * bawaan hardcoded — lihat public/js/game/GameFeature.jsx.
     */
    private function buildCustomQuestions(): array
    {
        return GameQuestion::where('is_active', true)
            ->get(['game_type', 'data'])
            ->groupBy('game_type')
            ->map(fn($rows) => $rows->pluck('data')->values())
            ->toArray();
    }

    public function members()
    {
        $user  = Auth::user();
        $users = $this->otherUsers($user);

        return response()->json($this->buildMembers($user, $users));
    }

    private function otherUsers($user)
    {
        return \App\Models\User::select('id', 'name', 'role', 'is_online', 'last_seen')
            ->where('id', '!=', $user->id)
            ->orderBy('name')
            ->get();
    }

    private function buildMembers($user, $users)
    {
        $ids = $users->pluck('id')->push($user->id);

        // User yang sedang di sesi 'waiting'/'active' (menunggu lawan siap
        // atau sedang bermain) — dipakai untuk badge "Sedang main" di daftar
        // lawan, supaya tidak menantang orang yang sudah sibuk di game lain.
        //
        // PENTING: sesi 'waiting'/'active' bisa "nyangkut" selamanya kalau
        // pemain menutup tab/mematikan koneksi sebelum sempat mengirim sinyal
        // /game/leave (beforeunload/sendBeacon bisa gagal terkirim). Tanpa
        // batas umur, user itu akan tampak "Sedang main" terus walau
        // sebenarnya sudah lama tidak ada aktivitas apa pun — makanya cuma
        // sesi yang masih "hidup" (dibuat dalam beberapa menit terakhir, ATAU
        // baru mulai) yang dihitung, bukan seluruh sesi waiting/active.
        $busySessionIds = GameSession::whereIn('status', ['waiting', 'active'])
            ->where(function ($q) {
                $q->where('created_at', '>=', now()->subMinutes(10))
                    ->orWhere('started_at', '>=', now()->subMinutes(30));
            })
            ->whereHas('participants', fn ($q) => $q->whereIn('user_id', $ids)->whereIn('status', ['invited', 'accepted']))
            ->pluck('id');
        $busyUserIds = GameSessionParticipant::whereIn('game_session_id', $busySessionIds)
            ->whereIn('status', ['invited', 'accepted'])
            ->pluck('user_id')
            ->unique();

        // Semua peserta 'accepted' dari sesi yang sudah selesai & melibatkan
        // salah satu user relevan (biar tidak scan seluruh tabel).
        $sessionIds = GameSession::where('status', 'finished')
            ->whereHas('participants', fn ($q) => $q->whereIn('user_id', $ids)->where('status', 'accepted'))
            ->pluck('id');

        $participants = GameSessionParticipant::whereIn('game_session_id', $sessionIds)
            ->where('status', 'accepted')
            ->get(['game_session_id', 'user_id', 'score']);

        $stats = [];
        foreach ($participants->groupBy('game_session_id') as $rows) {
            if ($rows->count() < 2) {
                continue;
            }
            $topScore = $rows->max('score');
            $winners  = $rows->where('score', $topScore);
            // Kalau semua skor sama (termasuk seri di antara semua pemain),
            // tidak dihitung menang/kalah untuk ronde ini.
            $isDraw = $winners->count() === $rows->count();

            foreach ($rows as $p) {
                $stats[$p->user_id] ??= ['menang' => 0, 'kalah' => 0];
                if ($isDraw) {
                    continue;
                }
                if ($winners->contains('user_id', $p->user_id)) {
                    $stats[$p->user_id]['menang']++;
                } else {
                    $stats[$p->user_id]['kalah']++;
                }
            }
        }

        return $users->map(fn($u) => [
            'id'         => $u->id,
            'nama'       => $u->name,
            'role'       => $u->role ?? 'anggota',
            'online'     => (bool) $u->is_online && $u->last_seen && $u->last_seen->gt(now()->subMinutes(3)),
            'sedangMain' => $busyUserIds->contains($u->id),
            'menang'     => $stats[$u->id]['menang'] ?? 0,
            'kalah'      => $stats[$u->id]['kalah'] ?? 0,
        ])->values();
    }

    private function buildWeeklyLeaderboard($user, $users)
    {
        $allUsers = $users->push($user)->keyBy('id');

        $sessionIds = GameSession::where('status', 'finished')
            ->where('finished_at', '>=', now()->startOfWeek())
            ->pluck('id', 'id');

        $participants = GameSessionParticipant::whereIn('game_session_id', $sessionIds->keys())
            ->where('status', 'accepted')
            ->whereIn('user_id', $allUsers->keys())
            ->get(['game_session_id', 'user_id', 'score']);

        $sessionGameType = GameSession::whereIn('id', $sessionIds->keys())->pluck('game_type', 'id');

        $points = [];
        foreach ($participants as $p) {
            $gameType = $sessionGameType[$p->game_session_id] ?? null;
            if (! $gameType) {
                continue;
            }
            $points[$p->user_id] ??= ['kuis' => 0, 'susun' => 0, 'tebak' => 0, 'memory' => 0];
            $points[$p->user_id][$gameType] += (int) $p->score;
        }

        return collect($points)
            ->map(function ($detail, $uid) use ($allUsers) {
                return [
                    'nama'   => $allUsers[$uid]->name,
                    'poin'   => array_sum($detail),
                    'detail' => $detail,
                ];
            })
            ->sortByDesc('poin')
            ->values();
    }

    public function serveJsx()
    {
        $path = public_path('js/game/GameFeature.jsx');
        abort_unless(file_exists($path), 404);
        return response(file_get_contents($path), 200)
            ->header('Content-Type', 'application/javascript')
            ->header('Cache-Control', 'public, max-age=3600');
    }
}
