<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\DailyVerse;
use App\Models\Devotion;
use App\Models\Member;
use App\Models\Schedule;

class DashboardController extends Controller
{
    public function index()
    {
        $totalAnggota    = Member::where('is_active', true)->count();
        $totalJadwal     = Schedule::count();
        $totalRenungan   = Devotion::where('status', 'approved')->count();
        $totalPengumuman = Announcement::where('is_published', true)->count();

        $ayatHarian     = DailyVerse::getToday();
        $jadwalTerdekat = Schedule::where('tanggal', '>=', today())
                            ->where('status', '!=', 'done')
                            ->orderBy('tanggal')->orderBy('jam')
                            ->first();

        $recentAnnouncements = Announcement::where('is_published', true)
                                ->latest()->take(3)->get();

        $upcomingSchedules = Schedule::where('tanggal', '>=', today())
                                ->orderBy('tanggal')->orderBy('jam')
                                ->take(5)->get();

        // Single GROUP BY query replacing 6 separate count queries
        $start = now()->subMonths(5)->startOfMonth();
        $rawStats = Devotion::where('status', 'approved')
            ->where('created_at', '>=', $start)
            ->selectRaw("TO_CHAR(created_at, 'YYYY-MM') as month_key, COUNT(*) as total")
            ->groupByRaw("TO_CHAR(created_at, 'YYYY-MM')")
            ->pluck('total', 'month_key');

        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $key   = $month->format('Y-m');
            $monthlyStats[] = [
                'label'    => $month->translatedFormat('M Y'),
                'devotion' => (int) ($rawStats[$key] ?? 0),
            ];
        }

        return view('dashboard', compact(
            'totalAnggota', 'totalJadwal', 'totalRenungan', 'totalPengumuman',
            'ayatHarian', 'jadwalTerdekat', 'recentAnnouncements',
            'upcomingSchedules', 'monthlyStats'
        ));
    }
}
