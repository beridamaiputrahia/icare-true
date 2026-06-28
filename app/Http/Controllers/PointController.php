<?php

namespace App\Http\Controllers;

use App\Models\UserPoint;
use App\Services\PointService;

class PointController extends Controller
{
    public function __construct(private PointService $pointService) {}

    public function index()
    {
        $user     = auth()->user();
        $points   = $user->points()->paginate(20);
        $progress = $this->pointService->getLevelProgress($user);
        $levels   = $this->pointService->getLevelsData();

        $weekPoints  = UserPoint::where('user_id', $user->id)->thisWeek()->sum('points');
        $monthPoints = UserPoint::where('user_id', $user->id)->thisMonth()->sum('points');

        return view('points.index', compact('user', 'points', 'progress', 'levels', 'weekPoints', 'monthPoints'));
    }
}
