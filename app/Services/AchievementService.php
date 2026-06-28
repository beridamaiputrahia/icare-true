<?php

namespace App\Services;

use App\Models\Achievement;
use App\Models\ActivityLog;
use App\Models\User;
use App\Models\UserAchievement;
use App\Models\UserPoint;
use Illuminate\Support\Str;

class AchievementService
{
    /**
     * Check and award all applicable achievements for a user.
     * Returns array of newly awarded achievements.
     */
    public function checkAndAward(User $user): array
    {
        $awarded = [];

        $awarded = array_merge(
            $awarded,
            $this->checkCategory($user, 'sharing_firman', $user->approved_devotions_count)
        );

        $awarded = array_merge(
            $awarded,
            $this->checkCategory($user, 'renungan', $user->total_devotions_count)
        );

        return $awarded;
    }

    /**
     * Check achievements for a specific category and count.
     */
    public function checkCategory(User $user, string $category, int $count): array
    {
        $awarded = [];

        $achievements = Achievement::where('category', $category)
            ->where('is_active', true)
            ->where('required_count', '<=', $count)
            ->orderBy('required_count')
            ->get();

        foreach ($achievements as $achievement) {
            if (!$user->hasAchievement($achievement->slug)) {
                UserAchievement::create([
                    'user_id'        => $user->id,
                    'achievement_id' => $achievement->id,
                    'count_at_award' => $count,
                    'awarded_at'     => now(),
                ]);

                ActivityLog::record(
                    $user->id,
                    'achievement',
                    "Mendapatkan badge: {$achievement->badge_label}",
                    $achievement
                );

                // Award points for earning achievement
                $ua = UserAchievement::where('user_id', $user->id)
                                     ->where('achievement_id', $achievement->id)
                                     ->first();
                if ($ua) {
                    app(PointService::class)->award($user, UserPoint::TYPE_ACHIEVEMENT, $ua);
                }

                $awarded[] = $achievement;
            }
        }

        return $awarded;
    }

    /**
     * Get progress data for all achievement categories.
     */
    public function getProgressForUser(User $user): array
    {
        $sharingCount  = $user->approved_devotions_count;
        $renunganCount = $user->total_devotions_count;

        return [
            'sharing_firman' => $this->buildProgress(
                $sharingCount,
                [3 => 'Domba Kecil', 6 => 'Murid', 9 => 'Pewarta', 12 => 'Penginjil Sejati'],
                $user
            ),
            'renungan' => $this->buildProgress(
                $renunganCount,
                [3 => 'Seeder', 5 => 'Growther', 8 => 'Pencerita', 12 => 'Panglima Injil'],
                $user
            ),
        ];
    }

    private function buildProgress(int $count, array $milestones, User $user): array
    {
        $steps = [];
        foreach ($milestones as $required => $label) {
            $achieved = $user->hasAchievement(Str::slug($label));
            $steps[]  = [
                'required' => $required,
                'label'    => $label,
                'achieved' => $achieved,
                'count'    => $count,
                'percent'  => min(100, $count >= $required ? 100 : round(($count / $required) * 100)),
            ];
        }

        $nextStep = null;
        foreach ($steps as $step) {
            if (!$step['achieved']) { $nextStep = $step; break; }
        }

        return [
            'count'    => $count,
            'steps'    => $steps,
            'next'     => $nextStep,
            'complete' => $nextStep === null,
        ];
    }
}
