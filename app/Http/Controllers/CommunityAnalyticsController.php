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
        $user = auth()->user();
        abort_unless($user?->isAdmin(), 403);

        // Superadmin melihat data GABUNGAN dari SEMUA I Care Group, bukan
        // cuma satu tenant -- users.tenant_id superadmin sendiri SELALU null
        // by design (dia tidak "milik" grup manapun), jadi User::where(
        // 'tenant_id', auth()->user()->tenant_id) sebelumnya berarti where(
        // 'tenant_id', null): tidak cocok dengan anggota manapun, membuat
        // seluruh dashboard analitik tampak kosong untuk superadmin. Admin/
        // ICL/CTL biasa TETAP di-scope ke tenant mereka sendiri seperti
        // semula -- withoutTenantScope() di bawah HANYA dipakai kalau role
        // superadmin.
        $isSuperadmin = $user->role === 'superadmin';
        $tenantId     = $isSuperadmin ? null : $user->tenant_id;

        // User TIDAK pakai trait BelongsToTenant (lihat komentar di
        // BelongsToTenant.php: "JANGAN pasang di User dan Tenant"), jadi
        // tidak ada withoutTenantScope() untuk dipanggil di sini -- filter
        // tenant_id memang satu-satunya mekanisme scoping untuk model ini,
        // dan untuk superadmin cukup TIDAK menambahkan filter itu sama
        // sekali supaya query mengembalikan anggota dari SEMUA tenant.
        $userQuery = fn () => $isSuperadmin ? User::query() : User::where('tenant_id', $tenantId);

        // Devotion/Prayer/UserAchievement/Album/UserPoint sudah otomatis
        // lintas-tenant untuk superadmin lewat BelongsToTenant::
        // resolveTenantId() (session active_tenant_id tetap menyaring 1
        // tenant kalau superadmin sedang "masuk sebagai" grup tertentu) --
        // withoutTenantScope() eksplisit di sini memastikan analitik
        // superadmin SELALU gabungan semua grup, terlepas dari tenant mana
        // yang kebetulan sedang aktif di tenant switcher-nya saat ini.

        // ── KPI Cards ───────────────────────────────────────
        $kpi = [
            'total_members'      => $userQuery()->where('is_active', true)->count(),
            'active_members'     => $userQuery()->where('is_active', true)->where('last_seen', '>=', now()->subDays(30))->count(),
            'total_devotions'    => ($isSuperadmin ? Devotion::withoutTenantScope() : Devotion::query())->where('status', 'approved')->count(),
            'total_prayers'      => ($isSuperadmin ? Prayer::withoutTenantScope() : Prayer::query())->whereIn('status', ['approved', 'answered'])->count(),
            'total_achievements' => ($isSuperadmin ? UserAchievement::withoutTenantScope() : UserAchievement::query())->count(),
            'total_points'       => $userQuery()->sum('total_points'),
            'total_sharing'      => ($isSuperadmin ? UserPoint::withoutTenantScope() : UserPoint::query())->where('type', UserPoint::TYPE_SHARING_FIRMAN)->count(),
            'total_albums'       => ($isSuperadmin ? Album::withoutTenantScope() : Album::query())->where('is_published', true)->count(),
        ];

        // ── Monthly Activity (12 months) ────────────────────
        $months = collect(range(11, 0))->map(fn ($i) => now()->subMonths($i));

        $monthlyActivity = [
            'labels'    => $months->map(fn ($m) => $m->translatedFormat('M Y'))->toArray(),
            'devotions' => $months->map(fn ($m) =>
                ($isSuperadmin ? Devotion::withoutTenantScope() : Devotion::query())
                        ->whereYear('created_at', $m->year)
                        ->whereMonth('created_at', $m->month)
                        ->where('status', 'approved')
                        ->count()
            )->toArray(),
            'prayers' => $months->map(fn ($m) =>
                ($isSuperadmin ? Prayer::withoutTenantScope() : Prayer::query())
                      ->whereYear('created_at', $m->year)
                      ->whereMonth('created_at', $m->month)
                      ->count()
            )->toArray(),
            'points' => $months->map(fn ($m) =>
                ($isSuperadmin ? UserPoint::withoutTenantScope() : UserPoint::query())
                         ->whereYear('created_at', $m->year)
                         ->whereMonth('created_at', $m->month)
                         ->sum('points')
            )->toArray(),
        ];

        // ── Member Growth ────────────────────────────────────
        $memberGrowth = [
            'labels' => $months->map(fn ($m) => $m->translatedFormat('M Y'))->toArray(),
            'data'   => $months->map(fn ($m) =>
                $userQuery()
                    ->whereYear('created_at', $m->year)
                    ->whereMonth('created_at', $m->month)
                    ->count()
            )->toArray(),
        ];

        // ── Level Distribution ───────────────────────────────
        $levelDist = collect($this->pointService->getLevelsData())
            ->map(fn ($l, $lvl) => [
                'label' => $l['name'],
                'count' => $userQuery()->where('level', $lvl)->where('is_active', true)->count(),
                'color' => $l['color'],
            ])
            ->values();

        // ── Point Type Distribution ──────────────────────────
        $pointTypeDist = ($isSuperadmin ? UserPoint::withoutTenantScope() : UserPoint::query())
            ->selectRaw('type, SUM(points) as total')
            ->groupBy('type')
            ->get()
            ->map(fn ($r) => [
                'label' => UserPoint::LABELS[$r->type] ?? $r->type,
                'total' => $r->total,
            ]);

        // ── Top Active Members ───────────────────────────────
        $topMembers = $userQuery()
            ->where('is_active', true)
            ->orderByDesc('total_points')
            ->take(5)
            ->get();

        // ── Achievement Distribution ─────────────────────────
        $achievementDistQuery = DB::table('user_achievements')
            ->join('achievements', 'achievements.id', '=', 'user_achievements.achievement_id')
            ->join('users', 'users.id', '=', 'user_achievements.user_id')
            ->selectRaw('achievements.name, COUNT(*) as count');

        if (! $isSuperadmin) {
            $achievementDistQuery->where('users.tenant_id', $tenantId);
        }

        $achievementDist = $achievementDistQuery
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
