<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $achievements = [
            // ── Kategori Sharing Firman ────────────────────────────
            [
                'name'           => 'Domba Kecil',
                'slug'           => 'domba-kecil',
                'category'       => 'sharing_firman',
                'description'    => 'Telah berbagi firman Tuhan sebanyak 3 kali.',
                'icon'           => 'fa-sheep',
                'color'          => '#10b981',
                'badge_label'    => 'Domba Kecil',
                'required_count' => 3,
                'order'          => 1,
            ],
            [
                'name'           => 'Murid',
                'slug'           => 'murid',
                'category'       => 'sharing_firman',
                'description'    => 'Telah berbagi firman Tuhan sebanyak 6 kali.',
                'icon'           => 'fa-graduation-cap',
                'color'          => '#3b82f6',
                'badge_label'    => 'Murid',
                'required_count' => 6,
                'order'          => 2,
            ],
            [
                'name'           => 'Pewarta',
                'slug'           => 'pewarta',
                'category'       => 'sharing_firman',
                'description'    => 'Telah berbagi firman Tuhan sebanyak 9 kali.',
                'icon'           => 'fa-megaphone',
                'color'          => '#8b5cf6',
                'badge_label'    => 'Pewarta',
                'required_count' => 9,
                'order'          => 3,
            ],
            [
                'name'           => 'Penginjil Sejati',
                'slug'           => 'penginjil-sejati',
                'category'       => 'sharing_firman',
                'description'    => 'Telah berbagi firman Tuhan sebanyak 12 kali.',
                'icon'           => 'fa-cross',
                'color'          => '#f59e0b',
                'badge_label'    => 'Penginjil Sejati',
                'required_count' => 12,
                'order'          => 4,
            ],

            // ── Kategori Renungan ──────────────────────────────────
            [
                'name'           => 'Seeder',
                'slug'           => 'seeder',
                'category'       => 'renungan',
                'description'    => 'Telah menulis 3 renungan.',
                'icon'           => 'fa-seedling',
                'color'          => '#22c55e',
                'badge_label'    => 'Seeder',
                'required_count' => 3,
                'order'          => 1,
            ],
            [
                'name'           => 'Growther',
                'slug'           => 'growther',
                'category'       => 'renungan',
                'description'    => 'Telah menulis 5 renungan.',
                'icon'           => 'fa-tree',
                'color'          => '#16a34a',
                'badge_label'    => 'Growther',
                'required_count' => 5,
                'order'          => 2,
            ],
            [
                'name'           => 'Pencerita',
                'slug'           => 'pencerita',
                'category'       => 'renungan',
                'description'    => 'Telah menulis 8 renungan.',
                'icon'           => 'fa-book-open',
                'color'          => '#0ea5e9',
                'badge_label'    => 'Pencerita',
                'required_count' => 8,
                'order'          => 3,
            ],
            [
                'name'           => 'Panglima Injil',
                'slug'           => 'panglima-injil',
                'category'       => 'renungan',
                'description'    => 'Telah menulis 12 renungan.',
                'icon'           => 'fa-shield-halved',
                'color'          => '#dc2626',
                'badge_label'    => 'Panglima Injil',
                'required_count' => 12,
                'order'          => 4,
            ],
        ];

        foreach ($achievements as $achievement) {
            Achievement::firstOrCreate(['slug' => $achievement['slug']], $achievement);
        }
    }
}
