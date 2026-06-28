<?php

namespace Database\Seeders;

use App\Models\Schedule;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $schedules = [
            [
                'nama_kegiatan' => 'Ibadah Raya Minggu',
                'tanggal'       => now()->addDays(3)->format('Y-m-d'),
                'jam'           => '09:00:00',
                'lokasi'        => 'Gedung Gereja IFGF Ungaran',
                'link_maps'     => 'https://maps.google.com',
                'pembicara'     => 'Pdt. Samuel Wijaya',
                'deskripsi'     => 'Ibadah raya minggu pagi bersama seluruh jemaat.',
                'status'        => 'upcoming',
                'created_by'    => 1,
            ],
            [
                'nama_kegiatan' => 'Connect Group Wilayah Timur',
                'tanggal'       => now()->addDays(7)->format('Y-m-d'),
                'jam'           => '19:00:00',
                'lokasi'        => 'Rumah Sdr. Budi - Jl. Merdeka No. 12',
                'link_maps'     => null,
                'pembicara'     => 'Pdt. Andreas Kurniawan',
                'deskripsi'     => 'Pertemuan connect group wilayah timur bulan ini.',
                'status'        => 'upcoming',
                'created_by'    => 1,
            ],
            [
                'nama_kegiatan' => 'Seminar Keluarga Kristiani',
                'tanggal'       => now()->addDays(14)->format('Y-m-d'),
                'jam'           => '08:00:00',
                'lokasi'        => 'Aula Gereja Lantai 2',
                'link_maps'     => 'https://maps.google.com',
                'pembicara'     => 'Ps. Yohanes & Istri',
                'deskripsi'     => 'Seminar tentang membangun keluarga yang sehat secara rohani dan jasmani.',
                'status'        => 'upcoming',
                'created_by'    => 1,
            ],
            [
                'nama_kegiatan' => 'Doa Puasa Bersama',
                'tanggal'       => now()->subDays(5)->format('Y-m-d'),
                'jam'           => '06:00:00',
                'lokasi'        => 'Gedung Gereja IFGF Ungaran',
                'link_maps'     => null,
                'pembicara'     => 'Tim Pastoral',
                'deskripsi'     => 'Ibadah doa dan puasa bersama seluruh jemaat.',
                'status'        => 'done',
                'created_by'    => 1,
            ],
        ];

        foreach ($schedules as $schedule) {
            Schedule::create($schedule);
        }
    }
}
