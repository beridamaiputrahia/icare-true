<?php

namespace App\Http\Controllers;

use App\Models\DailyVerse;
use Illuminate\Http\Request;

class DailyVerseController extends Controller
{
    public function index(Request $request)
    {
        $query = DailyVerse::with('creator')->latest();

        if ($request->filled('search')) {
            $query->where('referensi', 'like', '%' . $request->search . '%')
                  ->orWhere('ayat', 'like', '%' . $request->search . '%');
        }

        $verses = $query->paginate(10)->withQueryString();

        return view('daily-verses.index', compact('verses'));
    }

    public function create()
    {
        return view('daily-verses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ayat'             => 'required|string',
            'referensi'        => 'required|string|max:255',
            'renungan_singkat' => 'nullable|string',
            'tanggal'          => 'nullable|date',
            'is_active'        => 'boolean',
        ]);

        if ($validated['is_active'] ?? false) {
            DailyVerse::where('tanggal', $validated['tanggal'] ?? null)
                      ->update(['is_active' => false]);
        }

        $validated['is_active']  = $request->boolean('is_active', true);
        $validated['created_by'] = auth()->id();

        DailyVerse::create($validated);

        return redirect()->route('daily-verses.index')
            ->with('success', 'Ayat harian berhasil ditambahkan.');
    }

    public function show(DailyVerse $dailyVerse)
    {
        return view('daily-verses.show', compact('dailyVerse'));
    }

    public function edit(DailyVerse $dailyVerse)
    {
        return view('daily-verses.edit', compact('dailyVerse'));
    }

    public function update(Request $request, DailyVerse $dailyVerse)
    {
        $validated = $request->validate([
            'ayat'             => 'required|string',
            'referensi'        => 'required|string|max:255',
            'renungan_singkat' => 'nullable|string',
            'tanggal'          => 'nullable|date',
            'is_active'        => 'boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        $dailyVerse->update($validated);

        return redirect()->route('daily-verses.index')
            ->with('success', 'Ayat harian berhasil diperbarui.');
    }

    public function destroy(DailyVerse $dailyVerse)
    {
        $dailyVerse->delete();

        return redirect()->route('daily-verses.index')
            ->with('success', 'Ayat harian berhasil dihapus.');
    }
}
