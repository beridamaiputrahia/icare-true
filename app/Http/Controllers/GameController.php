<?php

namespace App\Http\Controllers;

use App\Models\GameSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameController extends Controller
{
    public function index()
    {
        $user  = Auth::user();
        $users = \App\Models\User::select('id', 'name', 'role', 'is_online')
            ->where('id', '!=', $user->id)
            ->orderBy('name')
            ->get();

        $finished = GameSession::where('status', 'finished')
            ->where(function ($q) use ($users) {
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

        $members = $users->map(fn($u) => [
            'id'       => $u->id,
            'nama'     => $u->name,
            'role'     => $u->role ?? 'anggota',
            'online'   => (bool) $u->is_online,
            'menang'   => $stats[$u->id]['menang'] ?? 0,
            'kalah'    => $stats[$u->id]['kalah'] ?? 0,
        ]);

        return view('game.index', compact('user', 'members'));
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
