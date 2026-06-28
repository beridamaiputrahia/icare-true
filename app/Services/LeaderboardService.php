<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserPoint;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class LeaderboardService
{
    public function weekly(int $limit = 10): Collection
    {
        return Cache::remember("leaderboard_weekly_{$limit}", 300, fn () =>
            $this->buildLeaderboard(fn ($q) => $q->thisWeek(), $limit)
        );
    }

    public function monthly(int $limit = 10): Collection
    {
        return Cache::remember("leaderboard_monthly_{$limit}", 600, fn () =>
            $this->buildLeaderboard(fn ($q) => $q->thisMonth(), $limit)
        );
    }

    public function yearly(int $limit = 10): Collection
    {
        return Cache::remember("leaderboard_yearly_{$limit}", 1800, fn () =>
            $this->buildLeaderboard(fn ($q) => $q->thisYear(), $limit)
        );
    }

    public function allTime(int $limit = 10): Collection
    {
        return Cache::remember("leaderboard_alltime_{$limit}", 600, fn () =>
            User::where('is_active', true)
                ->orderByDesc('total_points')
                ->take($limit)
                ->get()
                ->values()
                ->map(fn ($u, $i) => $this->buildEntry($u, $i + 1, $u->total_points))
        );
    }

    public function getUserRank(User $user): array
    {
        return [
            'weekly'   => $this->getRankForPeriod($user, 'weekly'),
            'monthly'  => $this->getRankForPeriod($user, 'monthly'),
            'all_time' => User::where('is_active', true)
                              ->where('total_points', '>', $user->total_points)
                              ->count() + 1,
        ];
    }

    public function flushCache(): void
    {
        foreach (['weekly', 'monthly', 'yearly', 'alltime'] as $period) {
            foreach ([10, 100, PHP_INT_MAX] as $limit) {
                Cache::forget("leaderboard_{$period}_{$limit}");
            }
        }
    }

    private function buildLeaderboard(callable $scope, int $limit): Collection
    {
        $rows = UserPoint::query()
            ->when(true, $scope)
            ->selectRaw('user_id, SUM(points) as period_points')
            ->groupBy('user_id')
            ->orderByDesc('period_points')
            ->take($limit)
            ->with('user')
            ->get();

        return $rows->values()
            ->map(fn ($row, $i) => $this->buildEntry($row->user, $i + 1, (int) $row->period_points))
            ->filter(fn ($e) => $e['user'] !== null)
            ->values();
    }

    private function buildEntry(?User $user, int $rank, int $points): array
    {
        if (!$user) return ['user' => null];

        $pointSvc = app(PointService::class);

        return [
            'rank'        => $rank,
            'user'        => $user,
            'points'      => $points,
            'level'       => $user->level,
            'level_name'  => $pointSvc->getLevelName($user->level),
            'level_icon'  => $pointSvc->getLevelIcon($user->level),
            'level_color' => $pointSvc->getLevelColor($user->level),
            // banner & highest_achievement are intentionally omitted here;
            // load them in the view only when needed to avoid N+1
        ];
    }

    private function getRankForPeriod(User $user, string $period): int
    {
        // Use a direct DB count instead of loading the full leaderboard
        $count = match($period) {
            'weekly'  => UserPoint::thisWeek()
                            ->selectRaw('user_id, SUM(points) as p')
                            ->groupBy('user_id')
                            ->havingRaw('SUM(points) > ?', [
                                UserPoint::thisWeek()
                                    ->where('user_id', $user->id)
                                    ->sum('points'),
                            ])
                            ->count(),
            'monthly' => UserPoint::thisMonth()
                            ->selectRaw('user_id, SUM(points) as p')
                            ->groupBy('user_id')
                            ->havingRaw('SUM(points) > ?', [
                                UserPoint::thisMonth()
                                    ->where('user_id', $user->id)
                                    ->sum('points'),
                            ])
                            ->count(),
            default   => 0,
        };

        return $count + 1;
    }
}
