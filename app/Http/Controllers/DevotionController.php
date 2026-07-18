<?php

namespace App\Http\Controllers;

use App\Events\DevotionApproved;
use App\Events\DevotionCreated;
use App\Models\Devotion;
use App\Models\UserPoint;
use App\Services\PointService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DevotionController extends Controller
{
    public function index(Request $request)
    {
        $query = Devotion::with('user')->latest();

        if (!auth()->user()->isAdmin()) {
            $query->where('user_id', auth()->id());
        }

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $devotions = $query->paginate(10)->withQueryString();

        return view('devotions.index', compact('devotions'));
    }

    public function create()
    {
        return view('devotions.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'          => 'required|string|max:255',
            'isi'            => 'required|string',
            'ayat_pendukung' => 'nullable|string|max:255',
            'gambar'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')
                ->store('devotions', config('filesystems.default'));
        }

        $validated['user_id'] = auth()->id();
        $validated['status']  = 'pending';

        $devotion = Devotion::create($validated);

        DevotionCreated::dispatch($devotion);

        // Award points for uploading devotion
        app(PointService::class)->award(auth()->user(), UserPoint::TYPE_DEVOTION_UPLOAD, $devotion);

        return redirect()->route('devotions.index')
            ->with('success', 'Renungan berhasil dikirim dan menunggu persetujuan admin.');
    }

    public function show(Devotion $devotion)
    {
        $this->authorizeAccess($devotion);

        return view('devotions.show', compact('devotion'));
    }

    public function edit(Devotion $devotion)
    {
        $this->authorizeOwner($devotion);

        return view('devotions.edit', compact('devotion'));
    }

    public function update(Request $request, Devotion $devotion)
    {
        $this->authorizeOwner($devotion);

        $validated = $request->validate([
            'judul'          => 'required|string|max:255',
            'isi'            => 'required|string',
            'ayat_pendukung' => 'nullable|string|max:255',
            'gambar'         => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($request->hasFile('gambar')) {
            if ($devotion->gambar) {
                Storage::disk(config('filesystems.default'))->delete($devotion->gambar);
            }
            $validated['gambar'] = $request->file('gambar')
                ->store('devotions', config('filesystems.default'));
        }

        $validated['status'] = 'pending';

        $devotion->update($validated);

        return redirect()->route('devotions.index')
            ->with('success', 'Renungan berhasil diperbarui dan dikembalikan ke status menunggu persetujuan.');
    }

    public function destroy(Devotion $devotion)
    {
        if (!auth()->user()->isAdmin() && $devotion->user_id !== auth()->id()) {
            abort(403);
        }

        if ($devotion->gambar) {
            Storage::disk(config('filesystems.default'))->delete($devotion->gambar);
        }

        $devotion->delete();

        return redirect()->route('devotions.index')
            ->with('success', 'Renungan berhasil dihapus.');
    }

    public function approve(Devotion $devotion)
    {
        $devotion->update([
            'status'        => 'approved',
            'approved_at'   => now(),
            'approved_by'   => auth()->id(),
            'catatan_admin' => null,
        ]);

        $fresh = $devotion->fresh();
        DevotionApproved::dispatch($fresh);

        // Award sharing firman points to devotion author
        app(PointService::class)->award($fresh->user, UserPoint::TYPE_SHARING_FIRMAN, $fresh);

        return redirect()->back()
            ->with('success', 'Renungan berhasil disetujui.');
    }

    public function reject(Request $request, Devotion $devotion)
    {
        $request->validate([
            'catatan_admin' => 'required|string|max:500',
        ]);

        $devotion->update([
            'status'        => 'rejected',
            'catatan_admin' => $request->catatan_admin,
            'approved_at'   => null,
            'approved_by'   => null,
        ]);

        return redirect()->back()
            ->with('success', 'Renungan telah ditolak.');
    }

    private function authorizeAccess(Devotion $devotion): void
    {
        if (!auth()->user()->isAdmin() && $devotion->user_id !== auth()->id()) {
            abort(403);
        }
    }

    private function authorizeOwner(Devotion $devotion): void
    {
        if ($devotion->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        if ($devotion->status === 'approved' && !auth()->user()->isAdmin()) {
            abort(403, 'Renungan yang sudah disetujui tidak dapat diedit.');
        }
    }
}
