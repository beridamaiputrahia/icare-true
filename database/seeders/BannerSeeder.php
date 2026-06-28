<?php

namespace Database\Seeders;

use App\Models\Banner;
use Illuminate\Database\Seeder;

class BannerSeeder extends Seeder
{
    public function run(): void
    {
        $banners = [
            // ── Role-based ─────────────────────────────────────────
            [
                'name'          => 'Leader',
                'slug'          => 'leader',
                'label'         => 'Leader',
                'description'   => 'Administrator komunitas',
                'icon'          => 'fa-crown',
                'color_from'    => '#b45309',
                'color_to'      => '#92400e',
                'text_color'    => '#fef3c7',
                'type'          => 'role',
                'condition_key' => 'admin',
                'priority'      => 100,
            ],
            [
                'name'          => 'Co-Leader',
                'slug'          => 'co-leader',
                'label'         => 'Co-Leader',
                'description'   => 'Wakil pemimpin komunitas',
                'icon'          => 'fa-star',
                'color_from'    => '#7c3aed',
                'color_to'      => '#4c1d95',
                'text_color'    => '#ede9fe',
                'type'          => 'role',
                'condition_key' => 'co_leader',
                'priority'      => 90,
            ],

            // ── Achievement-based ──────────────────────────────────
            [
                'name'          => 'Panglima Injil',
                'slug'          => 'panglima-injil',
                'label'         => 'Panglima Injil',
                'description'   => 'Penulis renungan terbanyak',
                'icon'          => 'fa-shield-halved',
                'color_from'    => '#dc2626',
                'color_to'      => '#7f1d1d',
                'text_color'    => '#fee2e2',
                'type'          => 'achievement',
                'condition_key' => 'panglima-injil',
                'priority'      => 85,
            ],
            [
                'name'          => 'Penginjil Sejati',
                'slug'          => 'penginjil-sejati',
                'label'         => 'Penginjil Sejati',
                'description'   => 'Berbagi firman 12 kali',
                'icon'          => 'fa-cross',
                'color_from'    => '#f59e0b',
                'color_to'      => '#92400e',
                'text_color'    => '#fef3c7',
                'type'          => 'achievement',
                'condition_key' => 'penginjil-sejati',
                'priority'      => 80,
            ],
            [
                'name'          => 'Pewarta',
                'slug'          => 'pewarta',
                'label'         => 'Pewarta',
                'description'   => 'Berbagi firman 9 kali',
                'icon'          => 'fa-megaphone',
                'color_from'    => '#8b5cf6',
                'color_to'      => '#4c1d95',
                'text_color'    => '#ede9fe',
                'type'          => 'achievement',
                'condition_key' => 'pewarta',
                'priority'      => 75,
            ],
            [
                'name'          => 'Pencerita',
                'slug'          => 'pencerita',
                'label'         => 'Pencerita',
                'description'   => 'Menulis 8 renungan',
                'icon'          => 'fa-book-open',
                'color_from'    => '#0ea5e9',
                'color_to'      => '#075985',
                'text_color'    => '#e0f2fe',
                'type'          => 'achievement',
                'condition_key' => 'pencerita',
                'priority'      => 70,
            ],
            [
                'name'          => 'Murid',
                'slug'          => 'murid',
                'label'         => 'Murid',
                'description'   => 'Berbagi firman 6 kali',
                'icon'          => 'fa-graduation-cap',
                'color_from'    => '#3b82f6',
                'color_to'      => '#1d4ed8',
                'text_color'    => '#dbeafe',
                'type'          => 'achievement',
                'condition_key' => 'murid',
                'priority'      => 65,
            ],
            [
                'name'          => 'Growther',
                'slug'          => 'growther',
                'label'         => 'Growther',
                'description'   => 'Menulis 5 renungan',
                'icon'          => 'fa-tree',
                'color_from'    => '#16a34a',
                'color_to'      => '#14532d',
                'text_color'    => '#dcfce7',
                'type'          => 'achievement',
                'condition_key' => 'growther',
                'priority'      => 60,
            ],
            [
                'name'          => 'Seeder',
                'slug'          => 'seeder',
                'label'         => 'Seeder',
                'description'   => 'Menulis 3 renungan',
                'icon'          => 'fa-seedling',
                'color_from'    => '#22c55e',
                'color_to'      => '#16a34a',
                'text_color'    => '#f0fdf4',
                'type'          => 'achievement',
                'condition_key' => 'seeder',
                'priority'      => 55,
            ],
            [
                'name'          => 'Domba Kecil',
                'slug'          => 'domba-kecil',
                'label'         => 'Domba Kecil',
                'description'   => 'Berbagi firman 3 kali',
                'icon'          => 'fa-dove',
                'color_from'    => '#10b981',
                'color_to'      => '#065f46',
                'text_color'    => '#d1fae5',
                'type'          => 'achievement',
                'condition_key' => 'domba-kecil',
                'priority'      => 50,
            ],

            // ── Member Status ──────────────────────────────────────
            [
                'name'          => 'Anggota Baru',
                'slug'          => 'anggota-baru',
                'label'         => 'Anggota Baru',
                'description'   => 'Bergabung dalam 30 hari terakhir',
                'icon'          => 'fa-star',
                'color_from'    => '#f97316',
                'color_to'      => '#c2410c',
                'text_color'    => '#fff7ed',
                'type'          => 'member_status',
                'condition_key' => 'new_member',
                'priority'      => 10,
            ],
            [
                'name'          => 'Anggota',
                'slug'          => 'anggota',
                'label'         => 'Anggota',
                'description'   => 'Anggota komunitas I Care True',
                'icon'          => 'fa-user',
                'color_from'    => '#64748b',
                'color_to'      => '#334155',
                'text_color'    => '#f1f5f9',
                'type'          => 'member_status',
                'condition_key' => 'default',
                'priority'      => 0,
            ],
        ];

        foreach ($banners as $banner) {
            Banner::firstOrCreate(['slug' => $banner['slug']], $banner);
        }
    }
}
