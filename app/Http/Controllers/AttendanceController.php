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
}
