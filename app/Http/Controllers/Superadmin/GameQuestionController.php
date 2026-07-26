<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\GameQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

/**
 * CRUD bank soal tambahan untuk 4 mini-game rohani (di luar bank soal bawaan
 * hardcoded di GameFeature.jsx). Soal di sini digabung dengan bank bawaan
 * saat game dimuat — lihat GameController::index() dan public/js/game/GameFeature.jsx.
 */
class GameQuestionController extends Controller
{
    private const GAME_LABELS = [
        'kuis'   => 'Kuis Adu Cepat',
        'susun'  => 'Susun Ayat',
        'tebak'  => 'Tebak Tokoh',
        'memory' => 'Memory Match',
    ];

    public function index(Request $request): View
    {
        $gameType = $request->get('game_type', 'kuis');

        $questions = GameQuestion::where('game_type', $gameType)
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('superadmin.game-questions.index', [
            'questions' => $questions,
            'gameType'  => $gameType,
            'gameLabels' => self::GAME_LABELS,
        ]);
    }

    public function create(Request $request): View
    {
        $gameType = $request->get('game_type', 'kuis');

        return view('superadmin.game-questions.create', [
            'gameType'   => $gameType,
            'gameLabels' => self::GAME_LABELS,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'game_type' => ['required', 'in:kuis,susun,tebak,memory'],
        ]);

        $data = $this->validateAndBuildData($request);

        GameQuestion::create([
            'game_type'  => $request->game_type,
            'data'       => $data,
            'created_by' => Auth::id(),
        ]);

        return redirect()
            ->route('superadmin.game-questions.index', ['game_type' => $request->game_type])
            ->with('status', 'Soal berhasil ditambahkan.');
    }

    public function edit(GameQuestion $gameQuestion): View
    {
        return view('superadmin.game-questions.edit', [
            'question'   => $gameQuestion,
            'gameLabels' => self::GAME_LABELS,
        ]);
    }

    public function update(Request $request, GameQuestion $gameQuestion): RedirectResponse
    {
        $data = $this->validateAndBuildData($request, $gameQuestion->game_type);

        $gameQuestion->update(['data' => $data]);

        return redirect()
            ->route('superadmin.game-questions.index', ['game_type' => $gameQuestion->game_type])
            ->with('status', 'Soal berhasil diperbarui.');
    }

    public function toggle(GameQuestion $gameQuestion): RedirectResponse
    {
        $gameQuestion->update(['is_active' => ! $gameQuestion->is_active]);

        return back()->with('status', $gameQuestion->is_active ? 'Soal diaktifkan.' : 'Soal dinonaktifkan.');
    }

    public function destroy(GameQuestion $gameQuestion): RedirectResponse
    {
        $gameType = $gameQuestion->game_type;
        $gameQuestion->delete();

        return redirect()
            ->route('superadmin.game-questions.index', ['game_type' => $gameType])
            ->with('status', 'Soal berhasil dihapus.');
    }

    /**
     * Validasi payload sesuai bentuk data per game_type, kembalikan array
     * siap simpan ke kolom `data` (JSON). $gameType dipaksa saat update
     * (tidak boleh berubah tipe game lewat form edit).
     */
    private function validateAndBuildData(Request $request, ?string $gameType = null): array
    {
        $gameType ??= $request->game_type;

        return match ($gameType) {
            'kuis' => $this->validateKuis($request),
            'susun' => $this->validateSusun($request),
            'tebak' => $this->validateTebak($request),
            'memory' => $this->validateMemory($request),
            default => abort(422, 'Tipe game tidak dikenal.'),
        };
    }

    private function validateKuis(Request $request): array
    {
        $v = $request->validate([
            'q'       => ['required', 'string', 'max:500'],
            'opsi'    => ['required', 'array', 'size:4'],
            'opsi.*'  => ['required', 'string', 'max:255'],
            'benar'   => ['required', 'integer', 'min:0', 'max:3'],
        ]);

        return ['q' => $v['q'], 'opsi' => array_values($v['opsi']), 'benar' => (int) $v['benar']];
    }

    private function validateSusun(Request $request): array
    {
        $v = $request->validate([
            'ref'  => ['required', 'string', 'max:100'],
            'teks' => ['required', 'string', 'max:500'],
        ]);

        return $v;
    }

    private function validateTebak(Request $request): array
    {
        $v = $request->validate([
            'jawaban'   => ['required', 'string', 'max:100'],
            'clues'     => ['required', 'array', 'size:3'],
            'clues.*'   => ['required', 'string', 'max:255'],
            'salah'     => ['required', 'array', 'size:3'],
            'salah.*'   => ['required', 'string', 'max:100'],
        ]);

        return [
            'jawaban' => $v['jawaban'],
            'clues'   => array_values($v['clues']),
            'salah'   => array_values($v['salah']),
        ];
    }

    private function validateMemory(Request $request): array
    {
        $v = $request->validate([
            'a' => ['required', 'string', 'max:100'],
            'b' => ['required', 'string', 'max:255'],
        ]);

        return $v;
    }
}
