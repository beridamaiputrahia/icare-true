<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AnnouncementController extends Controller
{
    public function index(Request $request)
    {
        $query = Announcement::with('creator')->latest();

        if ($request->filled('search')) {
            $query->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('penulis', 'like', '%' . $request->search . '%');
        }

        $announcements = $query->paginate(10)->withQueryString();

        return view('announcements.index', compact('announcements'));
    }

    public function create()
    {
        return view('announcements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'isi'          => 'required|string',
            'gambar'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'penulis'      => 'required|string|max:255',
            'is_published' => 'boolean',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')
                ->store('announcements', 'public');
        }

        $validated['is_published'] = $request->boolean('is_published', true);
        $validated['created_by']   = auth()->id();

        Announcement::create($validated);

        return redirect()->route('announcements.index')
            ->with('success', 'Pengumuman berhasil ditambahkan.');
    }

    public function show(Announcement $announcement)
    {
        return view('announcements.show', compact('announcement'));
    }

    public function edit(Announcement $announcement)
    {
        return view('announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'judul'        => 'required|string|max:255',
            'isi'          => 'required|string',
            'gambar'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'penulis'      => 'required|string|max:255',
            'is_published' => 'boolean',
        ]);

        if ($request->hasFile('gambar')) {
            if ($announcement->gambar) {
                Storage::disk('public')->delete($announcement->gambar);
            }
            $validated['gambar'] = $request->file('gambar')
                ->store('announcements', 'public');
        }

        $validated['is_published'] = $request->boolean('is_published', true);

        $announcement->update($validated);

        return redirect()->route('announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Announcement $announcement)
    {
        if ($announcement->gambar) {
            Storage::disk('public')->delete($announcement->gambar);
        }

        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }
}
