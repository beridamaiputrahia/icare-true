<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\Devotion;
use Illuminate\Support\Facades\DB;
use App\Models\Member;
use App\Models\Prayer;
use App\Models\Schedule;
use App\Models\User;

class StatisticsController extends Controller
{
    public function index()
    {
        // ── KPI Cards ─────────────────────────────────────────────
        $kpi = [
            'total_members'      => Member::where('is_active', true)->count(),
            'total_devotions'    => Devotion::where('status', 'approved')->count(),
            'total_prayers'      => Prayer::count(),
            'total_achievements' => DB::table('user_achievements')->count(),
        ];

        // ── Monthly Activity (12 months) ──────────────────────────
        $labels = $devotionsData = $prayersData = $memberGrowthData = [];

        for ($i = 11; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->translatedFormat('M Y');

            $devotionsData[] = Devotion::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->where('status', 'approved')->count();

            $prayersData[] = Prayer::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)->count();

            $memberGrowthData[] = Member::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)->count();
        }

        $monthlyActivity = [
            'labels'   => $labels,
            'devotions' => $devotionsData,
            'prayers'   => $prayersData,
        ];

        $memberGrowth = [
            'labels' => $labels,
            'data'   => $memberGrowthData,
        ];

        // ── Status Distributions ──────────────────────────────────
        $devotionStatus = [
            'pending'  => Devotion::where('status', 'pending')->count(),
            'approved' => Devotion::where('status', 'approved')->count(),
            'rejected' => Devotion::where('status', 'rejected')->count(),
        ];

        $prayerStatus = [
            'pending'  => Prayer::pending()->count(),
            'approved' => Prayer::approved()->count(),
            'answered' => Prayer::answered()->count(),
        ];

        // ── Top Writers ───────────────────────────────────────────
        $topWriters = User::withCount([
                'devotions as devotions_count' => fn ($q) => $q->where('status', 'approved')
            ])
            ->get()
            ->filter(fn ($u) => $u->devotions_count > 0)
            ->sortByDesc('devotions_count')
            ->take(5)
            ->values();

        // ── Achievement Stats ─────────────────────────────────────
        $achievementStats = Achievement::withCount('userAchievements as count')
            ->where('is_active', true)
            ->orderByDesc('count')
            ->take(6)->get()
            ->map(fn ($a) => [
                'name'     => $a->name,
                'icon'     => $a->icon,
                'color'    => $a->color,
                'category' => $a->category,
                'count'    => $a->count,
            ])->toArray();

        // ── Age Groups ────────────────────────────────────────────
        $ageGroups = [
            '< 20'   => Member::whereNotNull('tanggal_lahir')->where('is_active', true)
                              ->whereRaw("EXTRACT(YEAR FROM AGE(CURRENT_DATE, tanggal_lahir)) < 20")->count(),
            '20–30'  => Member::whereNotNull('tanggal_lahir')->where('is_active', true)
                              ->whereRaw("EXTRACT(YEAR FROM AGE(CURRENT_DATE, tanggal_lahir)) BETWEEN 20 AND 30")->count(),
            '31–40'  => Member::whereNotNull('tanggal_lahir')->where('is_active', true)
                              ->whereRaw("EXTRACT(YEAR FROM AGE(CURRENT_DATE, tanggal_lahir)) BETWEEN 31 AND 40")->count(),
            '> 40'   => Member::whereNotNull('tanggal_lahir')->where('is_active', true)
                              ->whereRaw("EXTRACT(YEAR FROM AGE(CURRENT_DATE, tanggal_lahir)) > 40")->count(),
        ];

        return view('statistics.index', compact(
            'kpi', 'monthlyActivity', 'memberGrowth',
            'devotionStatus', 'prayerStatus',
            'topWriters', 'achievementStats', 'ageGroups'
        ));
    }
}
