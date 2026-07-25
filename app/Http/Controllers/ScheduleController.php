<?php

namespace App\Http\Controllers;

use App\Managers\NotificationManager;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::with('creator')->latest('tanggal');

        if ($request->filled('search')) {
            $query->where('nama_kegiatan', 'like', '%' . $request->search . '%')
                  ->orWhere('lokasi', 'like', '%' . $request->search . '%')
                  ->orWhere('pembicara', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $schedules = $query->paginate(10)->withQueryString();

        return view('schedules.index', compact('schedules'));
    }

    public function create()
    {
        return view('schedules.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal'       => 'required|date',
            'jam'           => 'required',
            'lokasi'        => 'required|string|max:255',
            'link_maps'     => 'nullable|url',
            'pembicara'     => 'nullable|string|max:255',
            'deskripsi'     => 'nullable|string',
            'status'        => 'required|in:upcoming,ongoing,done',
        ]);

        $validated['created_by'] = auth()->id();

        $schedule = Schedule::create($validated);

        try {
            app(NotificationManager::class)->sendNewSchedule($schedule);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Gagal kirim notifikasi jadwal baru', [
                'message' => $e->getMessage(),
            ]);
        }

        return redirect()->route('schedules.index')
            ->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function show(Schedule $schedule)
    {
        return view('schedules.show', compact('schedule'));
    }

    public function edit(Schedule $schedule)
    {
        return view('schedules.edit', compact('schedule'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal'       => 'required|date',
            'jam'           => 'required',
            'lokasi'        => 'required|string|max:255',
            'link_maps'     => 'nullable|url',
            'pembicara'     => 'nullable|string|max:255',
            'deskripsi'     => 'nullable|string',
            'status'        => 'required|in:upcoming,ongoing,done',
        ]);

        $schedule->update($validated);

        return redirect()->route('schedules.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('schedules.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }
}
