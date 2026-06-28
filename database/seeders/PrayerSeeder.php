<?php

namespace Database\Seeders;

use App\Models\Prayer;
use Illuminate\Database\Seeder;

class PrayerSeeder extends Seeder
{
    public function run(): void
    {
        $prayers = [
            [
                'judul'        => 'Doa untuk Kesembuhan',
                'isi_doa'      => 'Ya Tuhan, kami memohon kesembuhan bagi saudara-saudara kami yang sedang sakit. Sentuhkan tangan penyembuhan-Mu dan pulihkan kesehatan mereka. Kami percaya bahwa tidak ada yang mustahil bagi-Mu. Amin.',
                'pengirim'     => 'Maria Santoso',
                'status'       => 'approved',
                'is_anonymous' => false,
                'user_id'      => 3,
                'approved_by'  => 1,
                'approved_at'  => now()->subDays(3),
            ],
            [
                'judul'        => 'Doa untuk Komunitas',
                'isi_doa'      => 'Tuhan yang baik, kami berdoa untuk komunitas kami agar terus bertumbuh dalam iman dan kasih. Pimpinlah setiap langkah kami dan jadikan kami berkat bagi lingkungan sekitar kami. Amin.',
                'pengirim'     => 'Anonim',
                'status'       => 'answered',
                'is_anonymous' => true,
                'user_id'      => 2,
                'approved_by'  => 1,
                'approved_at'  => now()->subDays(10),
                'answered_at'  => now()->subDays(2),
            ],
            [
                'judul'        => 'Doa untuk Keluarga',
                'isi_doa'      => 'Bapa yang di surga, kami membawa keluarga-keluarga dalam komunitas ini ke hadapan-Mu. Jagalah dan lindungi mereka. Pulihkan hubungan yang rusak dan perkuat yang lemah. Amin.',
                'pengirim'     => 'John Doe',
                'status'       => 'pending',
                'is_anonymous' => false,
                'user_id'      => 2,
            ],
        ];

        foreach ($prayers as $prayer) {
            Prayer::create($prayer);
        }
    }
}
