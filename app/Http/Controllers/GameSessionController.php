<?php

namespace App\Http\Controllers;

use App\Events\GameChallenged;
use App\Events\GameEnded;
use App\Events\GameMove;
use App\Events\GameStarted;
use App\Models\GameSession;
use App\Models\GameSessionParticipant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameSessionController extends Controller
{
    // POST /game/challenge — host mengundang 1-3 orang sekaligus (total 2-4 pemain termasuk host)
    public function challenge(Request $request)
    {
        $request->validate([
            'opponent_ids'   => 'required|array|min:1|max:3',
            'opponent_ids.*' => 'required|integer|exists:users,id',
            'game_type'      => 'required|in:kuis,susun,tebak,memory',
        ]);

        $hostId = Auth::id();
        $opponentIds = array_values(array_unique(array_diff($request->opponent_ids, [$hostId])));

        if (empty($opponentIds)) {
            return response()->json(['message' => 'Pilih minimal 1 lawan yang valid.'], 422);
        }

        // Batalkan sesi lama yang masih waiting dari user ini sebagai host
        $staleSessionIds = GameSession::where('host_id', $hostId)
            ->where('status', 'waiting')
            ->pluck('id');
        if ($staleSessionIds->isNotEmpty()) {
            GameSession::whereIn('id', $staleSessionIds)->update(['status' => 'declined']);
        }

        $session = GameSession::create([
            'code'      => GameSession::generateCode(),
            'host_id'   => $hostId,
            'game_type' => $request->game_type,
            'status'    => 'waiting',
            'seed'      => ['shuffle_key' => rand(1000, 9999)],
        ]);

        // Host otomatis "accepted" — dia sudah pasti ikut main.
        GameSessionParticipant::create([
            'game_session_id' => $session->id,
            'user_id'         => $hostId,
            'status'          => 'accepted',
        ]);

        $invitees = User::whereIn('id', $opponentIds)->get();
        foreach ($invitees as $invitee) {
            GameSessionParticipant::create([
                'game_session_id' => $session->id,
                'user_id'         => $invitee->id,
                'status'          => 'invited',
            ]);
            broadcast(new GameChallenged($session, $invitee));
        }

        return response()->json([
            'session_code' => $session->code,
            'status'       => 'waiting',
        ]);
    }

    // POST /game/respond — peserta yang diundang terima atau tolak
    public function respond(Request $request)
    {
        $request->validate([
            'session_code' => 'required|string',
            'accept'       => 'required|boolean',
        ]);

        $userId      = Auth::id();
        $session     = GameSession::where('code', $request->session_code)->where('status', 'waiting')->firstOrFail();
        $participant = $session->participants()->where('user_id', $userId)->where('status', 'invited')->firstOrFail();

        $participant->update(['status' => $request->accept ? 'accepted' : 'declined']);

        broadcast(new GameMove($session->fresh('participants.user'), $userId, [
            'type'   => $request->accept ? 'joined' : 'declined',
            'user_id' => $userId,
        ]));

        return response()->json(['status' => $request->accept ? 'accepted' : 'declined']);
    }

    // POST /game/start — host memulai sesi begitu peserta yang sudah accept dianggap cukup
    // (minimal 2 pemain termasuk host). Peserta yang belum sempat merespons (masih
    // 'invited') otomatis dianggap tidak ikut ronde ini.
    public function start(Request $request)
    {
        $request->validate(['session_code' => 'required|string']);

        $userId  = Auth::id();
        $session = GameSession::where('code', $request->session_code)
            ->where('host_id', $userId)
            ->where('status', 'waiting')
            ->firstOrFail();

        $acceptedCount = $session->participants()->where('status', 'accepted')->count();
        if ($acceptedCount < 2) {
            return response()->json(['message' => 'Minimal 1 lawan harus menerima tantangan dulu.'], 422);
        }

        $session->update(['status' => 'active', 'started_at' => now()]);

        broadcast(new GameStarted($session->fresh('participants.user')));

        return response()->json(['status' => 'active']);
    }

    // POST /game/move — kirim gerakan/skor pemain
    public function move(Request $request)
    {
        $request->validate([
            'session_code' => 'required|string',
            'payload'      => 'required|array',
        ]);

        $userId = Auth::id();
        // Terima juga status 'finished': pemain yang lebih lambat menyelesaikan
        // ronde terakhirnya bisa saja mengirim gerakan SETELAH sesi sudah
        // ditandai selesai oleh pemain lain. Menolak request itu (404) membuat
        // sisi yang lebih lambat macet permanen karena movenya tak pernah terkirim.
        $session = GameSession::whereIn('status', ['active', 'finished'])
            ->where('code', $request->session_code)
            ->firstOrFail();

        $participant = $session->participants()->where('user_id', $userId)->where('status', 'accepted')->firstOrFail();

        if (isset($request->payload['score'])) {
            $participant->update(['score' => (int) $request->payload['score']]);
        }

        if (! empty($request->payload['finished'])) {
            $participant->update(['finished' => true]);

            $session->load('participants');
            $accepted   = $session->participants->where('status', 'accepted');
            $allDone    = $accepted->every(fn ($p) => $p->finished);

            if ($allDone && $session->status !== 'finished') {
                $session->update(['status' => 'finished', 'finished_at' => now()]);
                broadcast(new GameEnded($session->fresh('participants.user')));
            }

            return response()->json(['status' => 'finished']);
        }

        broadcast(new GameMove($session->fresh('participants'), $userId, $request->payload));

        return response()->json(['status' => 'ok']);
    }

    // POST /game/leave — pemain sengaja keluar di tengah sesi 'active'
    // (tombol Keluar / tutup tab, lihat GameFeature.jsx sendBeacon on
    // beforeunload). Sesi TIDAK langsung diakhiri di sini — status peserta
    // cuma ditandai 'left' lalu pemain lain diberi tahu lewat GameMove
    // supaya mereka yang memutuskan lanjut atau akhiri lewat /game/resolve-leave.
    public function leave(Request $request)
    {
        $request->validate(['session_code' => 'required|string']);

        $userId  = Auth::id();
        $session = GameSession::where('code', $request->session_code)
            ->where('status', 'active')
            ->firstOrFail();

        $participant = $session->participants()->where('user_id', $userId)->where('status', 'accepted')->firstOrFail();
        $participant->update(['status' => 'left']);

        broadcast(new GameMove($session->fresh('participants.user'), $userId, [
            'type'        => 'player_left',
            'user_id'     => $userId,
            'user_name'   => $participant->user->name ?? '',
        ]));

        return response()->json(['status' => 'left']);
    }

    // POST /game/resolve-leave — salah satu pemain yang TERSISA memutuskan
    // kelanjutan sesi setelah ada yang keluar (lihat leave() di atas).
    // Siapa pun yang tersisa boleh mengirim ini duluan; yang pertama sampai
    // ke server yang menentukan (session sudah tidak 'active' lagi setelah
    // diputuskan 'end', jadi request kedua yang menyusul akan gagal wajar).
    public function resolveLeave(Request $request)
    {
        $request->validate([
            'session_code' => 'required|string',
            'action'       => 'required|in:continue,end',
        ]);

        $userId  = Auth::id();
        $session = GameSession::where('code', $request->session_code)
            ->where('status', 'active')
            ->firstOrFail();

        // Pastikan pengirim memang masih peserta aktif (bukan yang sudah keluar).
        $session->participants()->where('user_id', $userId)->where('status', 'accepted')->firstOrFail();

        if ($request->action === 'end') {
            $session->update(['status' => 'declined']);
            broadcast(new GameMove($session->fresh('participants.user'), $userId, [
                'type' => 'session_ended_by_leave',
            ]));

            return response()->json(['status' => 'ended']);
        }

        // "continue": sesi tetap aktif, sisa pemain lanjut. Broadcast supaya
        // dialog pilihan di layar pemain lain otomatis tertutup.
        broadcast(new GameMove($session->fresh('participants.user'), $userId, [
            'type' => 'session_continued',
        ]));

        // Kalau kebetulan semua pemain yang MASIH 'accepted' sudah selesai
        // ronde mereka duluan sebelum keputusan ini turun, sesi harus segera
        // ditutup sekarang (bukan menunggu move berikutnya yang mungkin
        // tidak akan pernah datang lagi).
        $session->load('participants');
        $accepted = $session->participants->where('status', 'accepted');
        if ($accepted->isNotEmpty() && $accepted->every(fn ($p) => $p->finished) && $session->status !== 'finished') {
            $session->update(['status' => 'finished', 'finished_at' => now()]);
            broadcast(new GameEnded($session->fresh('participants.user')));
        }

        return response()->json(['status' => 'continued']);
    }

    // GET /game/session/{code} — polling fallback & info sesi
    public function show(string $code)
    {
        $userId  = Auth::id();
        $session = GameSession::where('code', $code)
            ->with(['host:id,name', 'participants.user:id,name'])
            ->firstOrFail();

        abort_unless($session->isParticipant($userId), 403);

        return response()->json([
            'code'      => $session->code,
            'game_type' => $session->game_type,
            'status'    => $session->status,
            'seed'      => $session->seed,
            'host'      => $session->host->only('id', 'name'),
            'players'   => $session->participants->map(fn ($p) => [
                'id'       => $p->user_id,
                'nama'     => $p->user->name,
                'status'   => $p->status,
                'score'    => $p->score,
                'finished' => $p->finished,
            ])->values(),
        ]);
    }

    // GET /game/pending — cek apakah ada tantangan masuk untuk user ini
    public function pending()
    {
        $participant = GameSessionParticipant::where('user_id', Auth::id())
            ->where('status', 'invited')
            ->whereHas('session', fn ($q) => $q->where('status', 'waiting'))
            ->with('session.host:id,name')
            ->latest()
            ->first();

        if (! $participant) {
            return response()->json(['pending' => false]);
        }

        return response()->json([
            'pending'      => true,
            'session_code' => $participant->session->code,
            'game_type'    => $participant->session->game_type,
            'host_name'    => $participant->session->host->name,
            'host_id'      => $participant->session->host_id,
        ]);
    }
}
