<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ScheduleSeeder::class,
            AnnouncementSeeder::class,
            MemberSeeder::class,
            DailyVerseSeeder::class,
            DevotionSeeder::class,
            AchievementSeeder::class,
            BannerSeeder::class,
            PrayerSeeder::class,
        ]);
    }
}
