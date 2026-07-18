<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Devotion;
use App\Models\Prayer;
use App\Models\User;
use App\Models\UserAchievement;
use App\Models\UserPoint;
use App\Services\PointService;
use Illuminate\Support\Facades\DB;

class CommunityAnalyticsController extends Controller
{
    public function __construct(private PointService $pointService) {}

    public function index()
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        $tenantId = auth()->user()->tenant_id;

        // ── KPI Cards ───────────────────────────────────────
        $kpi = [
            'total_members'      => User::where('tenant_id', $tenantId)->where('is_active', true)->count(),
            'active_members'     => User::where('tenant_id', $tenantId)->where('is_active', true)->where('last_seen', '>=', now()->subDays(30))->count(),
            'total_devotions'    => Devotion::where('status', 'approved')->count(),
            'total_prayers'      => Prayer::whereIn('status', ['approved', 'answered'])->count(),
            'total_achievements' => UserAchievement::count(),
            'total_points'       => User::where('tenant_id', $tenantId)->sum('total_points'),
            'total_sharing'      => UserPoint::where('type', UserPoint::TYPE_SHARING_FIRMAN)->count(),
            'total_albums'       => Album::where('is_published', true)->count(),
        ];

        // ── Monthly Activity (12 months) ────────────────────
        $months = collect(range(11, 0))->map(fn ($i) => now()->subMonths($i));

        $monthlyActivity = [
            'labels'    => $months->map(fn ($m) => $m->translatedFormat('M Y'))->toArray(),
            'devotions' => $months->map(fn ($m) =>
                Devotion::whereYear('created_at', $m->year)
                        ->whereMonth('created_at', $m->month)
                        ->where('status', 'approved')
                        ->count()
            )->toArray(),
            'prayers' => $months->map(fn ($m) =>
                Prayer::whereYear('created_at', $m->year)
                      ->whereMonth('created_at', $m->month)
                      ->count()
            )->toArray(),
            'points' => $months->map(fn ($m) =>
                UserPoint::whereYear('created_at', $m->year)
                         ->whereMonth('created_at', $m->month)
                         ->sum('points')
            )->toArray(),
        ];

        // ── Member Growth ────────────────────────────────────
        $memberGrowth = [
            'labels' => $months->map(fn ($m) => $m->translatedFormat('M Y'))->toArray(),
            'data'   => $months->map(fn ($m) =>
                User::where('tenant_id', $tenantId)
                    ->whereYear('created_at', $m->year)
                    ->whereMonth('created_at', $m->month)
                    ->count()
            )->toArray(),
        ];

        // ── Level Distribution ───────────────────────────────
        $levelDist = collect($this->pointService->getLevelsData())
            ->map(fn ($l, $lvl) => [
                'label' => $l['name'],
                'count' => User::where('tenant_id', $tenantId)->where('level', $lvl)->where('is_active', true)->count(),
                'color' => $l['color'],
            ])
            ->values();

        // ── Point Type Distribution ──────────────────────────
        $pointTypeDist = UserPoint::selectRaw('type, SUM(points) as total')
            ->groupBy('type')
            ->get()
            ->map(fn ($r) => [
                'label' => UserPoint::LABELS[$r->type] ?? $r->type,
                'total' => $r->total,
            ]);

        // ── Top Active Members ───────────────────────────────
        $topMembers = User::where('tenant_id', $tenantId)
            ->where('is_active', true)
            ->orderByDesc('total_points')
            ->take(5)
            ->get();

        // ── Achievement Distribution ─────────────────────────
        $achievementDist = DB::table('user_achievements')
            ->join('achievements', 'achievements.id', '=', 'user_achievements.achievement_id')
            ->join('users', 'users.id', '=', 'user_achievements.user_id')
            ->selectRaw('achievements.name, COUNT(*) as count')
            ->where('users.tenant_id', $tenantId)
            ->groupBy('achievements.id', 'achievements.name')
            ->orderByDesc('count')
            ->take(8)
            ->get();

        return view('analytics.index', compact(
            'kpi', 'monthlyActivity', 'memberGrowth',
            'levelDist', 'pointTypeDist', 'topMembers', 'achievementDist'
        ));
    }
}
