<?php

namespace App\Http\Controllers;

use App\Managers\NotificationManager;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index(Request $request)
    {
        $query = Schedule::with(['creator', 'speaker'])->latest('tanggal');

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
        $members = $this->speakerOptions();

        return view('schedules.create', compact('members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal'       => 'required|date',
            'jam'           => 'required',
            'lokasi'        => 'required|string|max:255',
            'link_maps'     => 'nullable|url',
            'pembicara_id'  => 'nullable|exists:users,id',
            'deskripsi'     => 'nullable|string',
            'status'        => 'required|in:upcoming,ongoing,done',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['pembicara']  = $validated['pembicara_id']
            ? User::find($validated['pembicara_id'])->name
            : null;

        $schedule = Schedule::create($validated);

        try {
            app(NotificationManager::class)->sendNewSchedule($schedule);

            if ($schedule->pembicara_id) {
                app(NotificationManager::class)->sendSpeakerAssigned($schedule, $schedule->speaker);
            }
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
        $schedule->load(['attendances.member', 'speaker']);

        return view('schedules.show', compact('schedule'));
    }

    public function edit(Schedule $schedule)
    {
        $members = $this->speakerOptions();

        return view('schedules.edit', compact('schedule', 'members'));
    }

    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'nama_kegiatan' => 'required|string|max:255',
            'tanggal'       => 'required|date',
            'jam'           => 'required',
            'lokasi'        => 'required|string|max:255',
            'link_maps'     => 'nullable|url',
            'pembicara_id'  => 'nullable|exists:users,id',
            'deskripsi'     => 'nullable|string',
            'status'        => 'required|in:upcoming,ongoing,done',
        ]);

        $previousSpeakerId = $schedule->pembicara_id;

        $validated['pembicara'] = $validated['pembicara_id']
            ? User::find($validated['pembicara_id'])->name
            : null;

        $schedule->update($validated);

        if ($schedule->pembicara_id && $schedule->pembicara_id !== $previousSpeakerId) {
            try {
                app(NotificationManager::class)->sendSpeakerAssigned($schedule, $schedule->speaker);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Gagal kirim notifikasi pembicara', [
                    'message' => $e->getMessage(),
                ]);
            }
        }

        return redirect()->route('schedules.index')
            ->with('success', 'Jadwal berhasil diperbarui.');
    }

    /** Anggota yang bisa dipilih sebagai pembicara (user aktif dengan profil anggota). */
    private function speakerOptions()
    {
        return User::where('is_active', true)
            ->whereHas('member')
            ->orderBy('name')
            ->get(['id', 'name']);
    }

    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()->route('schedules.index')
            ->with('success', 'Jadwal berhasil dihapus.');
    }
}
