<?php

namespace App\Http\Controllers;

use App\Models\GameQuestion;
use App\Models\GameSession;
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
        $finished = GameSession::where('status', 'finished')
            ->where(function ($q) use ($users, $user) {
                $ids = $users->pluck('id')->push($user->id);
                $q->whereIn('challenger_id', $ids)->orWhereIn('opponent_id', $ids);
            })
            ->get(['challenger_id', 'opponent_id', 'score_challenger', 'score_opponent']);

        $stats = [];
        foreach ($finished as $session) {
            foreach (['challenger_id' => 'score_challenger', 'opponent_id' => 'score_opponent'] as $idField => $scoreField) {
                $uid = $session->$idField;
                $stats[$uid] ??= ['menang' => 0, 'kalah' => 0];
            }
            if ($session->score_challenger === $session->score_opponent) {
                continue;
            }
            $winnerId = $session->score_challenger > $session->score_opponent
                ? $session->challenger_id
                : $session->opponent_id;
            $loserId = $winnerId === $session->challenger_id
                ? $session->opponent_id
                : $session->challenger_id;
            $stats[$winnerId]['menang']++;
            $stats[$loserId]['kalah']++;
        }

        return $users->map(fn($u) => [
            'id'       => $u->id,
            'nama'     => $u->name,
            'role'     => $u->role ?? 'anggota',
            'online'   => (bool) $u->is_online && $u->last_seen && $u->last_seen->gt(now()->subMinutes(3)),
            'menang'   => $stats[$u->id]['menang'] ?? 0,
            'kalah'    => $stats[$u->id]['kalah'] ?? 0,
        ])->values();
    }

    private function buildWeeklyLeaderboard($user, $users)
    {
        $allUsers = $users->push($user)->keyBy('id');

        $sessions = GameSession::where('status', 'finished')
            ->where('finished_at', '>=', now()->startOfWeek())
            ->get(['challenger_id', 'opponent_id', 'game_type', 'score_challenger', 'score_opponent']);

        $gameTypes = ['kuis', 'susun', 'tebak', 'memory'];
        $points    = [];

        foreach ($sessions as $session) {
            foreach ([
                $session->challenger_id => $session->score_challenger,
                $session->opponent_id   => $session->score_opponent,
            ] as $uid => $score) {
                if (! $allUsers->has($uid)) {
                    continue;
                }
                $points[$uid] ??= ['kuis' => 0, 'susun' => 0, 'tebak' => 0, 'memory' => 0];
                $points[$uid][$session->game_type] += (int) $score;
            }
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
