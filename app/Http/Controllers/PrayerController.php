<?php

namespace App\Http\Controllers;

use App\Managers\NotificationManager;
use App\Models\ActivityLog;
use App\Models\Prayer;
use App\Models\UserPoint;
use App\Services\PointService;
use Illuminate\Http\Request;

class PrayerController extends Controller
{
    public function index(Request $request)
    {
        $query = Prayer::with('user')->latest();

        if (!auth()->user()->isAdmin()) {
            // Users see their own + approved/answered prayers
            $userId = auth()->id();
            $query->where(function ($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->orWhereIn('status', ['approved', 'answered']);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        $prayers = $query->paginate(10)->withQueryString();

        $stats = [
            'pending'  => Prayer::pending()->count(),
            'approved' => Prayer::approved()->count(),
            'answered' => Prayer::answered()->count(),
        ];

        return view('prayers.index', compact('prayers', 'stats'));
    }

    public function create()
    {
        return view('prayers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'isi_doa'      => 'required|string',
            'pengirim'     => 'nullable|string|max:150',
            'is_anonymous' => 'boolean',
        ]);

        $validated['user_id']      = auth()->id();
        $validated['status']       = 'pending';
        $validated['is_anonymous'] = $request->boolean('is_anonymous');

        if ($validated['is_anonymous']) {
            $validated['pengirim'] = 'Anonim';
        } elseif (empty($validated['pengirim'])) {
            $validated['pengirim'] = auth()->user()->name;
        }

        $prayer = Prayer::create($validated);

        ActivityLog::record(auth()->id(), 'prayer_created', "Mengajukan doa: '{$prayer->judul}'", $prayer);

        // Award points for prayer request
        app(PointService::class)->award(auth()->user(), UserPoint::TYPE_PRAYER_REQUEST, $prayer);

        try {
            app(NotificationManager::class)->sendNewPrayerRequest($prayer);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Gagal kirim notifikasi doa baru', [
                'message' => $e->getMessage(),
            ]);
        }

        return redirect()->route('prayers.index')
            ->with('success', 'Doa berhasil dikirim dan menunggu persetujuan admin.');
    }

    public function show(Prayer $prayer)
    {
        $this->authorize('view', $prayer);
        return view('prayers.show', compact('prayer'));
    }

    public function edit(Prayer $prayer)
    {
        $this->authorize('update', $prayer);
        return view('prayers.edit', compact('prayer'));
    }

    public function update(Request $request, Prayer $prayer)
    {
        $this->authorize('update', $prayer);

        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'isi_doa'      => 'required|string',
            'pengirim'     => 'nullable|string|max:150',
            'is_anonymous' => 'boolean',
        ]);

        $validated['is_anonymous'] = $request->boolean('is_anonymous');
        $validated['status']       = 'pending';

        $prayer->update($validated);

        return redirect()->route('prayers.index')
            ->with('success', 'Doa berhasil diperbarui.');
    }

    public function destroy(Prayer $prayer)
    {
        $this->authorize('delete', $prayer);
        $prayer->delete();

        return redirect()->route('prayers.index')
            ->with('success', 'Doa berhasil dihapus.');
    }

    public function approve(Prayer $prayer)
    {
        $this->authorize('approve', $prayer);

        $prayer->update([
            'status'      => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
        ]);

        ActivityLog::record($prayer->user_id, 'prayer_approved', "Doa '{$prayer->judul}' disetujui", $prayer);

        return redirect()->back()->with('success', 'Doa telah disetujui dan ditambahkan ke daftar doa.');
    }

    public function markAnswered(Prayer $prayer)
    {
        $this->authorize('markAnswered', $prayer);

        $prayer->update([
            'status'      => 'answered',
            'answered_at' => now(),
        ]);

        ActivityLog::record($prayer->user_id, 'prayer_answered', "Doa '{$prayer->judul}' telah dijawab Tuhan!", $prayer);

        return redirect()->back()->with('success', 'Doa telah ditandai sebagai dijawab Tuhan. Puji Tuhan!');
    }
}
