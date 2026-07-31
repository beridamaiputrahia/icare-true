<?php

namespace App\Http\Controllers;

use App\Events\GameChallenged;
use App\Events\GameEnded;
use App\Events\GameMove;
use App\Events\GameStarted;
use App\Models\GameQuestionHistory;
use App\Models\GameSession;
use App\Models\GameSessionParticipant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GameSessionController extends Controller
{
    // Berapa banyak soal terakhir (per game_type per tenant) yang harus
    // dihindari supaya tidak muncul lagi di match berikutnya. Dipatok relatif
    // terhadap kebutuhan per match (lihat SOAL_PER_MATCH) bukan angka tetap,
    // supaya bank kecil pun tidak langsung habis di 1-2 match seperti dulu.
    private const SOAL_PER_MATCH = [
        'kuis'   => 7,
        'susun'  => 5,
        'tebak'  => 10,
        'memory' => 32,
    ];
    private const HINDARI_KELIPATAN = 3; // hindari soal dari 3 match terakhir

    // Memory Match level 2/3 butuh 32 pasang, sementara bank bawaan
    // (BANK_KARTU di GameFeature.jsx) juga PERSIS 32 entri. Kalau target
    // hindari dihitung sama seperti game lain (32*3=96), frontend akan
    // selalu menghindari LEBIH BANYAK dari sisa kandidat yang ada — begitu
    // hindari-list menyisakan kurang dari 32 kandidat, siapkanKartuSeed()
    // membuang seluruh filter dan pakai bank penuh tanpa hindari sama
    // sekali (lihat kandidat.length<n fallback), jadi 32 kartu yang sama
    // selalu terulang persis di match berikutnya — mekanisme anti-ulang
    // untuk Memory jadi tidak pernah benar-benar aktif. Batasi target
    // hindari Memory jauh lebih kecil (cukup hindari match SEBELUMNYA
    // saja, bukan 3x lipat) supaya selalu ada kandidat tersisa untuk
    // difilter secara nyata.
    private const HINDARI_KELIPATAN_PER_GAME = [
        'memory' => 0.5, // ~16 kartu terakhir dihindari, bukan 96 — bank 32 masih menyisakan kandidat
    ];

    private function ambilKeyDihindari(int $tenantId, string $gameType): array
    {
        $kelipatan = self::HINDARI_KELIPATAN_PER_GAME[$gameType] ?? self::HINDARI_KELIPATAN;
        $batas     = (int) floor((self::SOAL_PER_MATCH[$gameType] ?? 10) * $kelipatan);

        return GameQuestionHistory::where('tenant_id', $tenantId)
            ->where('game_type', $gameType)
            ->orderByDesc('used_at')
            ->limit($batas)
            ->pluck('question_key')
            ->unique()
            ->values()
            ->toArray();
    }

    private function catatKeyDipakai(int $tenantId, string $gameType, array $keys): void
    {
        if (empty($keys)) {
            return;
        }

        $now  = now();
        $rows = array_map(fn ($key) => [
            'tenant_id'    => $tenantId,
            'game_type'    => $gameType,
            'question_key' => (string) $key,
            'used_at'      => $now,
        ], array_values(array_unique($keys)));

        GameQuestionHistory::insert($rows);

        // Rumah tangga ringan: buang riwayat lama yang sudah jauh melebihi
        // kebutuhan anti-repeat, supaya tabel ini tidak bertumbuh tak terbatas.
        $batas = (self::SOAL_PER_MATCH[$gameType] ?? 10) * self::HINDARI_KELIPATAN * 5;
        $idLama = GameQuestionHistory::where('tenant_id', $tenantId)
            ->where('game_type', $gameType)
            ->orderByDesc('used_at')
            ->skip($batas)->take(500)
            ->pluck('id');
        if ($idLama->isNotEmpty()) {
            GameQuestionHistory::whereIn('id', $idLama)->delete();
        }
    }

    // POST /game/challenge — host mengundang 1-3 orang sekaligus (total 2-4 pemain termasuk host)
    public function challenge(Request $request)
    {
        $request->validate([
            'opponent_ids'   => 'required|array|min:1|max:3',
            'opponent_ids.*' => 'required|integer|exists:users,id',
            'game_type'      => 'required|in:kuis,susun,tebak,memory',
            'level'          => 'nullable|integer|in:1,2,3', // Memory Match: 1=4x4, 2=8x8, 3=8x8+reshuffle. Diabaikan utk game_type lain.
        ]);

        $hostId = Auth::id();
        $opponentIds = array_values(array_unique(array_diff($request->opponent_ids, [$hostId])));

        if (empty($opponentIds)) {
            return response()->json(['message' => 'Pilih minimal 1 lawan yang valid.'], 422);
        }

        // Server-side sekarang juga menyaring lawan yang sedang sibuk main
        // game lain — sebelumnya ini HANYA dicek di frontend (PilihLawan),
        // yang bisa basi kalau lawan mulai match lain persis di jendela
        // waktu antara render daftar & tombol "Tantang" ditekan. Tanpa
        // pengecekan server, tantangan tetap terkirim ke lawan yang sudah
        // sibuk, membuatnya "diterima" ke dua sesi sekaligus.
        $busySessionIds = GameSession::whereIn('status', ['waiting', 'active'])
            ->where(function ($q) {
                $q->where('created_at', '>=', now()->subMinutes(10))
                    ->orWhere('started_at', '>=', now()->subMinutes(30));
            })
            ->whereHas('participants', fn ($q) => $q->whereIn('user_id', $opponentIds)->whereIn('status', ['invited', 'accepted'])->where('finished', false))
            ->pluck('id');
        $busyOpponentIds = GameSessionParticipant::whereIn('game_session_id', $busySessionIds)
            ->whereIn('user_id', $opponentIds)
            ->whereIn('status', ['invited', 'accepted'])
            ->where('finished', false)
            ->pluck('user_id')
            ->unique()
            ->all();

        $opponentIds = array_values(array_diff($opponentIds, $busyOpponentIds));

        if (empty($opponentIds)) {
            return response()->json(['message' => 'Semua lawan yang dipilih sedang bermain game lain.'], 422);
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
            'seed'      => [
                'shuffle_key' => rand(1000, 9999),
                'level'       => $request->game_type === 'memory' ? ($request->level ?? 1) : null,
            ],
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

    // POST /game/cancel — host membatalkan sesi 'waiting' yang belum
    // dimulai (mis. menekan "Kembali" di lobi, atau berubah pikiran tidak
    // jadi main). SEBELUM endpoint ini ada, tidak ada cara server tahu host
    // membatalkan — sesi 'waiting' tetap tersimpan begitu saja, sehingga
    // SEMUA yang diundang (status 'invited'/'accepted') tetap terhitung
    // "sedang bermain" oleh buildMembers()/challenge() sampai jendela waktu
    // 10 menit (created_at) habis, padahal host sudah jelas-jelas batal.
    // Host sendiri pun ikut nyangkut "sedang main" karena dia sendiri
    // adalah participant 'accepted' di sesi yang sama.
    public function cancel(Request $request)
    {
        $request->validate(['session_code' => 'required|string']);

        $session = GameSession::where('code', $request->session_code)
            ->where('host_id', Auth::id())
            ->where('status', 'waiting')
            ->firstOrFail();

        $session->update(['status' => 'declined']);

        broadcast(new GameMove($session->fresh('participants.user'), Auth::id(), [
            'type' => 'session_ended_by_leave',
        ]));

        return response()->json(['status' => 'declined']);
    }

    // POST /game/respond — peserta yang diundang terima atau tolak
    public function respond(Request $request)
    {
        $request->validate([
            'session_code' => 'required|string',
            'accept'       => 'required|boolean',
        ]);

        $userId      = Auth::id();
        // withoutTenantScope: peserta yang diundang bisa dari tenant lain
        // (lihat challenge() — undangan lintas-tenant kini diizinkan), jadi
        // sesi ini bisa saja "milik" tenant lain dari sudut pandang si
        // penerima undangan. Keamanan tetap terjaga karena firstOrFail() di
        // bawah mensyaratkan baris participant miliknya sendiri untuk sesi
        // INI secara spesifik — bukan izin melihat sesi tenant lain manapun.
        // Selain 'invited' (respons pertama kali), terima juga peserta yang
        // statusnya sudah 'accepted' MEMBATALKAN keikutsertaannya (accept:
        // false) sebelum host menekan Mulai — mis. menekan "Kembali" di
        // lobi tunggu setelah sempat menerima. Tanpa ini baris participant
        // tetap 'accepted' walau dia sudah pergi dari lobi, sehingga dia
        // tetap terhitung "sedang bermain" sampai sesi berakhir/timeout.
        $statusDiterima = $request->accept ? ['invited'] : ['invited', 'accepted'];

        $session     = GameSession::withoutTenantScope()->where('code', $request->session_code)->where('status', 'waiting')->firstOrFail();
        $participant = $session->participants()->where('user_id', $userId)->whereIn('status', $statusDiterima)->firstOrFail();

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

        // Sisipkan daftar soal yang harus dihindari (baru dipakai tenant ini
        // beberapa match terakhir) ke dalam seed, supaya semua pemain di
        // sesi ini menyaring bank soal yang SAMA sebelum seeded-shuffle —
        // kalau tiap device menyaring sendiri-sendiri pakai riwayat lokal,
        // hasilnya bisa beda dan soal antar pemain jadi tidak sinkron lagi.
        $tenantId = GameSession::resolveTenantId();
        $seed     = $session->seed ?? [];
        if ($tenantId) {
            $seed['avoid_keys'] = $this->ambilKeyDihindari($tenantId, $session->game_type);
        }
        // Peserta yang masih 'invited' (belum sempat merespons) TIDAK ikut
        // ronde ini — tandai 'declined' di sini juga, bukan cuma dianggap
        // tidak ikut di frontend. Tanpa ini baris participant-nya tetap
        // 'invited' selama sesi 'active' berjalan, sehingga badge "Sedang
        // main" tetap menyala untuk orang yang sebenarnya tidak pernah
        // benar-benar main ronde ini (lihat buildMembers() di GameController).
        $session->participants()->where('status', 'invited')->update(['status' => 'declined']);

        $session->update(['status' => 'active', 'started_at' => now(), 'seed' => $seed]);

        broadcast(new GameStarted($session->fresh('participants.user')));

        return response()->json(['status' => 'active']);
    }

    // POST /game/record-questions — dipanggil frontend segera setelah
    // siapkanXSeed() memilih daftar soal final untuk match yang baru dimulai,
    // supaya soal2 itu tercatat sebagai "baru dipakai" dan otomatis dihindari
    // di match2 berikutnya (lihat ambilKeyDihindari/start() di atas).
    public function recordQuestions(Request $request)
    {
        $request->validate([
            'session_code' => 'required|string',
            'keys'         => 'required|array|min:1',
            'keys.*'       => 'required|string',
        ]);

        $userId  = Auth::id();
        // withoutTenantScope: dipanggil frontend dari SEMUA pemain (bukan
        // cuma host) — cek host_id di bawah baru menyaring siapa yang
        // benar-benar mencatat. Peserta lintas-tenant tetap harus lolos
        // firstOrFail() di sini dulu, bukan 404 duluan.
        $session = GameSession::withoutTenantScope()
            ->where('code', $request->session_code)
            ->where('status', 'active')
            ->firstOrFail();

        // Semua pemain menerima soal yang identik (seeded shuffle), jadi
        // cukup HOST yang mencatat — kalau semua pemain ikut mencatat,
        // soal yang sama akan tercatat 2-4x (duplikat per pemain di sesi ini).
        if ($session->host_id !== $userId) {
            return response()->json(['status' => 'ok']);
        }

        $tenantId = GameSession::resolveTenantId();
        if ($tenantId) {
            $this->catatKeyDipakai($tenantId, $session->game_type, $request->keys);
        }

        return response()->json(['status' => 'ok']);
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
        // withoutTenantScope: peserta (bukan host) sesi lintas-tenant.
        $session = GameSession::withoutTenantScope()
            ->whereIn('status', ['active', 'finished'])
            ->where('code', $request->session_code)
            ->firstOrFail();

        $participant = $session->participants()->where('user_id', $userId)->where('status', 'accepted')->firstOrFail();

        if (isset($request->payload['score'])) {
            $skorBaru = (int) $request->payload['score'];

            // MITIGASI: soal & jawaban benar sepenuhnya ada di client (untuk
            // sinkronisasi seeded-shuffle antar pemain), jadi server TIDAK
            // bisa memverifikasi apakah skor yang dikirim itu benar-benar
            // hasil jawaban yang sah — perbaikan menyeluruh butuh server
            // ikut jadi wasit (tahu soal & jawaban), di luar cakupan
            // perbaikan ini. Sebagai lapis pertahanan murah tanpa mengubah
            // arsitektur: tolak nilai yang jelas mustahil (negatif, atau di
            // atas skor maksimum teoretis skenario TERBAIK di game manapun
            // — Memory level 3: 32 pasang * 250 = 8000, game lain jauh di
            // bawah itu) supaya request iseng "score: 999999999" langsung
            // ditolak, bukan mencegah kecurangan yang lebih halus.
            if ($skorBaru < 0 || $skorBaru > 8000) {
                return response()->json(['message' => 'Skor tidak valid.'], 422);
            }

            // Skor per pemain SEHARUSNYA hanya naik (tiap ronde menambah,
            // tidak pernah dikurangi) — turun drastis dari nilai sebelumnya
            // adalah tanda payload yang dipalsukan/rusak, bukan gerakan sah.
            if ($skorBaru < $participant->score) {
                return response()->json(['message' => 'Skor tidak valid.'], 422);
            }

            $participant->update(['score' => $skorBaru]);
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
        // withoutTenantScope: peserta (bukan host) sesi lintas-tenant.
        $session = GameSession::withoutTenantScope()
            ->where('code', $request->session_code)
            ->where('status', 'active')
            ->firstOrFail();

        $participant = $session->participants()->where('user_id', $userId)->where('status', 'accepted')->firstOrFail();
        $participant->update(['status' => 'left']);

        broadcast(new GameMove($session->fresh('participants.user'), $userId, [
            'type'        => 'player_left',
            'user_id'     => $userId,
            'user_name'   => $participant->user->name ?? '',
        ]));

        // Kalau SEMUA pemain yang masih 'accepted' (bukan yang baru saja
        // 'left' ini) sudah selesai ronde mereka sebelum orang ini keluar,
        // sesi harus langsung ditutup di sini juga — bukan cuma di
        // resolveLeave() (yang baru terpicu kalau ada pemain TERSISA yang
        // menekan "Lanjutkan"/"Akhiri" di dialog). Tanpa ini, urutan
        // "pemain lain selesai duluan → pemain terakhir keluar tanpa pernah
        // finished" membuat sesi macet selamanya di status 'active', dan
        // pemain yang sudah selesai tadi tetap tampak "Sedang main".
        $session->load('participants');
        $accepted = $session->participants->where('status', 'accepted');
        if ($accepted->isNotEmpty() && $accepted->every(fn ($p) => $p->finished) && $session->status !== 'finished') {
            $session->update(['status' => 'finished', 'finished_at' => now()]);
            broadcast(new GameEnded($session->fresh('participants.user')));
        }

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
        // withoutTenantScope: peserta (bukan host) sesi lintas-tenant.
        $session = GameSession::withoutTenantScope()
            ->where('code', $request->session_code)
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
        // withoutTenantScope: dipanggil oleh SEMUA peserta termasuk yang
        // bukan dari tenant host (lihat challenge() — undangan lintas-tenant
        // kini diizinkan). abort_unless isParticipant() di bawah tetap jadi
        // penjaga akses — bukan tenant-nya yang menentukan boleh lihat atau
        // tidak, tapi apakah dia benar-benar terdaftar sebagai peserta sesi INI.
        $session = GameSession::withoutTenantScope()
            ->where('code', $code)
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
        // GameSessionParticipant sendiri tidak ber-tenant, tapi relasi
        // session() menuju GameSession yang ber-tenant — whereHas/with di
        // bawah diam-diam menerapkan scope tenant HOST lewat relasi itu,
        // jadi tantangan dari host tenant lain (kini diizinkan, lihat
        // challenge()) akan hilang tanpa pesan error kalau tidak
        // withoutGlobalScope di sini.
        //
        // PENTING: sesi 'waiting' yang host-nya tidak pernah menekan Mulai
        // ATAU tidak pernah dibatalkan (mis. host keluar app di tengah
        // proses undang) bisa nyangkut selamanya — tanpa batas umur, baris
        // 'invited' ini akan terus muncul sebagai "tantangan masuk" tak
        // terhingga meski sebenarnya sudah lama ditinggalkan.
        // Ambil yang PALING LAMA (bukan ->latest()), bukan yang paling
        // baru: kalau user ditantang 2 orang berbeda dalam window yang
        // sama, ->latest() bikin tantangan yang lebih baru SELALU menang
        // menutupi yang lebih lama — tantangan lama itu bisa lewat batas
        // 10 menit di atas dan hilang tanpa pernah sempat terlihat sama
        // sekali oleh user. Prioritaskan yang paling dekat kedaluwarsa.
        $participant = GameSessionParticipant::where('user_id', Auth::id())
            ->where('status', 'invited')
            ->whereHas('session', fn ($q) => $q->withoutGlobalScope('tenant')->where('status', 'waiting')->where('created_at', '>=', now()->subMinutes(10)))
            ->with(['session' => fn ($q) => $q->withoutGlobalScope('tenant')->with('host:id,name')])
            ->oldest()
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

    // GET /game/pending-host — cek apakah user ini adalah host dari sesi
    // 'waiting' yang masih hidup, supaya kalau dia TIDAK SENGAJA me-refresh
    // halaman /game persis sesudah /game/challenge berhasil terkirim (sesi
    // sudah dibuat & undangan sudah sampai ke lawan), dia tidak kembali ke
    // Hub kosong seolah tantangannya hilang — dia diarahkan otomatis
    // kembali ke lobi tunggu yang sama, bukan harus mengundang ulang dari
    // nol (yang bakal mengirim undangan KEDUA ke lawan yang sama).
    public function pendingHost()
    {
        $session = GameSession::where('host_id', Auth::id())
            ->where('status', 'waiting')
            ->where('created_at', '>=', now()->subMinutes(10))
            ->with('participants.user:id,name')
            ->latest()
            ->first();

        if (! $session) {
            return response()->json(['pending' => false]);
        }

        return response()->json([
            'pending'      => true,
            'session_code' => $session->code,
            'game_type'    => $session->game_type,
            'lawan'        => $session->participants->where('user_id', '!=', Auth::id())->map(fn ($p) => [
                'id'   => $p->user_id,
                'nama' => $p->user->name,
            ])->values(),
        ]);
    }
}
