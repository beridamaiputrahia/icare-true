<?php

namespace App\Services;

use App\Models\Banner;
use App\Models\User;

class BannerService
{
    // Priority order: highest number = shown first
    private const ACHIEVEMENT_BANNER_MAP = [
        'penginjil-sejati' => 'penginjil-sejati',
        'panglima-injil'   => 'panglima-injil',
        'pewarta'          => 'pewarta',
        'pencerita'        => 'pencerita',
        'murid'            => 'murid',
        'growther'         => 'growther',
        'seeder'           => 'seeder',
        'domba-kecil'      => 'domba-kecil',
    ];

    /**
     * Resolve the highest-priority banner for a user.
     */
    public function resolveForUser(User $user): ?Banner
    {
        // 1. Admin role → Leader
        if ($user->isAdmin()) {
            return Banner::where('slug', 'leader')->where('is_active', true)->first();
        }

        // 2. Check achievement-based banners (highest first)
        foreach (self::ACHIEVEMENT_BANNER_MAP as $achievementSlug => $bannerSlug) {
            if ($user->hasAchievement($achievementSlug)) {
                $banner = Banner::where('slug', $bannerSlug)->where('is_active', true)->first();
                if ($banner) return $banner;
            }
        }

        // 3. New member (joined within 30 days)
        if ($user->created_at->diffInDays(now()) <= 30) {
            return Banner::where('slug', 'anggota-baru')->where('is_active', true)->first();
        }

        // 4. Default member banner
        return Banner::where('slug', 'anggota')->where('is_active', true)->first();
    }

    /**
     * Get a banner by slug with a fallback.
     */
    public function getBanner(string $slug): ?Banner
    {
        return Banner::where('slug', $slug)->where('is_active', true)->first();
    }

    public function getDefaultBannerStyle(): string
    {
        return 'background: linear-gradient(135deg, #2563eb, #7c3aed);';
    }
}
