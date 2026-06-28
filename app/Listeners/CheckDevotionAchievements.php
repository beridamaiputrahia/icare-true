<?php

namespace App\Listeners;

use App\Events\DevotionApproved;
use App\Events\DevotionCreated;
use App\Models\ActivityLog;
use App\Services\AchievementService;
use Illuminate\Events\Dispatcher;

class CheckDevotionAchievements
{
    public function __construct(private AchievementService $achievementService) {}

    public function handleApproved(DevotionApproved $event): void
    {
        $devotion = $event->devotion;
        $user     = $devotion->user;
        if (!$user) return;

        ActivityLog::record($user->id, 'devotion_approved', "Renungan '{$devotion->judul}' disetujui", $devotion);
        $this->achievementService->checkAndAward($user);
    }

    public function handleCreated(DevotionCreated $event): void
    {
        $devotion = $event->devotion;
        $user     = $devotion->user;
        if (!$user) return;

        ActivityLog::record($user->id, 'devotion_created', "Mengupload renungan '{$devotion->judul}'", $devotion);
        $this->achievementService->checkAndAward($user);
    }

    public function subscribe(Dispatcher $events): array
    {
        return [
            DevotionApproved::class => 'handleApproved',
            DevotionCreated::class  => 'handleCreated',
        ];
    }
}
