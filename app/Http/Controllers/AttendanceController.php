<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Member;
use App\Models\Schedule;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function scan(Schedule $schedule)
    {
        return view('attendances.scan', compact('schedule'));
    }

    public function store(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'member_id' => 'required|integer|exists:members,id',
        ]);

        $member = Member::findOrFail($validated['member_id']);

        $existing = Attendance::where('schedule_id', $schedule->id)
            ->where('member_id', $member->id)
            ->first();

        if ($existing) {
            return response()->json([
                'status'  => 'duplicate',
                'message' => "{$member->nama_lengkap} sudah tercatat hadir.",
            ]);
        }

        Attendance::create([
            'schedule_id' => $schedule->id,
            'member_id'   => $member->id,
            'scanned_by'  => auth()->id(),
            'scanned_at'  => now(),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => "{$member->nama_lengkap} berhasil dicatat hadir.",
            'name'    => $member->nama_lengkap,
        ]);
    }

    /** Halaman kelola kehadiran: absen manual, edit waktu, hapus. */
    public function index(Schedule $schedule)
    {
        $schedule->load(['attendances.member']);

        $attendedIds = $schedule->attendances->pluck('member_id');

        $members = Member::where('is_active', true)
            ->whereNotIn('id', $attendedIds)
            ->orderBy('nama_lengkap')
            ->get();

        return view('attendances.index', compact('schedule', 'members'));
    }

    /** Catat kehadiran manual (tanpa scan QR) untuk satu atau lebih anggota sekaligus. */
    public function storeManual(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'member_ids'   => 'required|array|min:1',
            'member_ids.*' => 'integer|exists:members,id',
        ]);

        $already = Attendance::where('schedule_id', $schedule->id)
            ->whereIn('member_id', $validated['member_ids'])
            ->pluck('member_id');

        foreach ($validated['member_ids'] as $memberId) {
            if ($already->contains($memberId)) continue;

            Attendance::create([
                'schedule_id' => $schedule->id,
                'member_id'   => $memberId,
                'scanned_by'  => auth()->id(),
                'scanned_at'  => now(),
            ]);
        }

        return redirect()->route('attendances.index', $schedule)
            ->with('success', 'Kehadiran manual berhasil dicatat.');
    }

    /** Ubah waktu kehadiran (mis. koreksi jam scan yang salah). */
    public function update(Request $request, Schedule $schedule, Attendance $attendance)
    {
        abort_unless($attendance->schedule_id === $schedule->id, 404);

        $validated = $request->validate([
            'scanned_at' => 'required|date',
        ]);

        $attendance->update(['scanned_at' => $validated['scanned_at']]);

        return redirect()->route('attendances.index', $schedule)
            ->with('success', 'Waktu kehadiran berhasil diperbarui.');
    }

    /** Hapus catatan kehadiran (mis. salah scan / salah catat). */
    public function destroy(Schedule $schedule, Attendance $attendance)
    {
        abort_unless($attendance->schedule_id === $schedule->id, 404);

        $attendance->delete();

        return redirect()->route('attendances.index', $schedule)
            ->with('success', 'Catatan kehadiran berhasil dihapus.');
    }
}
