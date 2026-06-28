<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\LeaderboardService;
use App\Services\PointService;

class LeaderboardController extends Controller
{
    public function __construct(
        private LeaderboardService $leaderboard,
        private PointService       $points
    ) {}

    public function index()
    {
        $user    = auth()->user();
        $weekly  = $this->leaderboard->weekly(10);
        $monthly = $this->leaderboard->monthly(10);
        $yearly  = $this->leaderboard->yearly(10);
        $allTime = $this->leaderboard->allTime(10);
        $myRanks = $this->leaderboard->getUserRank($user);
        $levels  = $this->points->getLevelsData();

        // Find my all-time rank & points without loading entire leaderboard
        $myAllTimeRank   = $myRanks['all_time'];
        $myTotalPoints   = $user->total_points;
        $myLevelProgress = $this->points->getLevelProgress($user);

        return view('leaderboard.index', compact(
            'weekly', 'monthly', 'yearly', 'allTime',
            'myRanks', 'levels', 'myAllTimeRank', 'myTotalPoints', 'myLevelProgress'
        ));
    }
}
