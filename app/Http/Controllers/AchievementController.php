<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use App\Models\User;
use App\Services\AchievementService;

class AchievementController extends Controller
{
    public function __construct(private AchievementService $achievementService) {}

    public function index()
    {
        $user       = auth()->user();
        $progress   = $this->achievementService->getProgressForUser($user);
        $myBadges   = $user->achievements()->with('pivot')->get();
        $allBadges  = Achievement::where('is_active', true)->orderBy('category')->orderBy('required_count')->get();

        return view('achievements.index', compact('user', 'progress', 'myBadges', 'allBadges'));
    }

    public function userDetail(User $user)
    {
        // Admin can view any user; users can only view themselves
        if (!auth()->user()->isAdmin() && auth()->id() !== $user->id) {
            abort(403);
        }

        $progress     = $this->achievementService->getProgressForUser($user);
        $myBadges     = $user->achievements()->orderBy('required_count')->get();
        $allBadges    = Achievement::where('is_active', true)->orderBy('category')->orderBy('required_count')->get();
        $activityLogs = $user->activityLogs()->take(20)->get();

        return view('achievements.user', compact('user', 'progress', 'myBadges', 'allBadges', 'activityLogs'));
    }
}
