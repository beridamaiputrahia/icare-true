<?php

namespace App\Http\Controllers;

use App\Events\GameChallenged;
use App\Events\GameEnded;
use App\Events\GameMove;
use App\Events\GameStarted;
use App\Models\GameSession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameSessionController extends Controller
{
    // POST /game/challenge  — penantang kirim tantangan
    public function challenge(Request $request)
    {
        $request->validate([
            'opponent_id' => 'required|exists:users,id|different:' . Auth::id(),
            'game_type'   => 'required|in:kuis,susun,tebak,memory',
        ]);

        // Batalkan sesi lama yang masih waiting dari user ini
        GameSession::where('challenger_id', Auth::id())
            ->where('status', 'waiting')
            ->update(['status' => 'declined']);

        $session = GameSession::create([
            'code'          => GameSession::generateCode(),
            'challenger_id' => Auth::id(),
            'opponent_id'   => $request->opponent_id,
            'game_type'     => $request->game_type,
            'status'        => 'waiting',
            'seed'          => ['shuffle_key' => rand(1000, 9999)],
        ]);

        broadcast(new GameChallenged($session));

        return response()->json([
            'session_code' => $session->code,
            'status'       => 'waiting',
        ]);
    }

    // POST /game/respond  — lawan terima atau tolak
    public function respond(Request $request)
    {
        $request->validate([
            'session_code' => 'required|string',
            'accept'       => 'required|boolean',
        ]);

        $session = GameSession::where('code', $request->session_code)
            ->where('opponent_id', Auth::id())
            ->where('status', 'waiting')
            ->firstOrFail();

        if (! $request->accept) {
            $session->update(['status' => 'declined']);
            broadcast(new GameMove($session, Auth::id(), ['type' => 'declined']));
            return response()->json(['status' => 'declined']);
        }

        $session->update(['status' => 'active', 'started_at' => now()]);
        broadcast(new GameStarted($session));

        return response()->json([
            'status'    => 'active',
            'seed'      => $session->seed,
            'game_type' => $session->game_type,
        ]);
    }

    // POST /game/move  — kirim gerakan pemain
    public function move(Request $request)
    {
        $request->validate([
            'session_code' => 'required|string',
            'payload'      => 'required|array',
        ]);

        $userId  = Auth::id();
        // Terima juga status 'finished': pemain yang lebih lambat menyelesaikan
        // ronde terakhirnya bisa saja mengirim gerakan SETELAH lawannya sudah
        // menandai sesi selesai duluan. Menolak request itu (404) membuat sisi
        // yang lebih lambat macet permanen karena movenya tak pernah terkirim.
        $session = GameSession::where('code', $request->session_code)
            ->whereIn('status', ['active', 'finished'])
            ->where(function ($q) use ($userId) {
                $q->where('challenger_id', $userId)->orWhere('opponent_id', $userId);
            })
            ->firstOrFail();

        // Update skor jika payload membawa skor baru
        if (isset($request->payload['score'])) {
            $role  = $session->roleOf($userId);
            $field = $role === 'challenger' ? 'score_challenger' : 'score_opponent';
            $session->update([$field => $request->payload['score']]);
            $session->refresh();
        }

        // Cek apakah game selesai
        if (isset($request->payload['finished']) && $request->payload['finished']) {
            if ($session->status !== 'finished') {
                $session->update(['status' => 'finished', 'finished_at' => now()]);
                broadcast(new GameEnded($session));
            }
            return response()->json(['status' => 'finished']);
        }

        broadcast(new GameMove($session, $userId, $request->payload));

        return response()->json(['status' => 'ok']);
    }

    // GET /game/session/{code}  — polling fallback & info sesi
    public function show(string $code)
    {
        $userId  = Auth::id();
        $session = GameSession::where('code', $code)
            ->where(function ($q) use ($userId) {
                $q->where('challenger_id', $userId)->orWhere('opponent_id', $userId);
            })
            ->with(['challenger:id,name', 'opponent:id,name'])
            ->firstOrFail();

        return response()->json([
            'code'             => $session->code,
            'game_type'        => $session->game_type,
            'status'           => $session->status,
            'seed'             => $session->seed,
            'score_challenger' => $session->score_challenger,
            'score_opponent'   => $session->score_opponent,
            'my_role'          => $session->roleOf($userId),
            'challenger'       => $session->challenger->only('id', 'name'),
            'opponent'         => $session->opponent->only('id', 'name'),
        ]);
    }

    // GET /game/pending  — cek apakah ada tantangan masuk
    public function pending()
    {
        $session = GameSession::where('opponent_id', Auth::id())
            ->where('status', 'waiting')
            ->with('challenger:id,name')
            ->latest()
            ->first();

        if (! $session) {
            return response()->json(['pending' => false]);
        }

        return response()->json([
            'pending'         => true,
            'session_code'    => $session->code,
            'game_type'       => $session->game_type,
            'challenger_name' => $session->challenger->name,
            'challenger_id'   => $session->challenger_id,
        ]);
    }
}
