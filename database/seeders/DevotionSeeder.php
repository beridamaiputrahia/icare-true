<?php

namespace Database\Seeders;

use App\Models\Devotion;
use Illuminate\Database\Seeder;

class DevotionSeeder extends Seeder
{
    public function run(): void
    {
        $devotions = [
            [
                'judul'          => 'Iman yang Menggerakkan Gunung',
                'isi'            => '<p>Dalam perjalanan hidup kita, seringkali kita menghadapi masalah yang tampak sebesar gunung. Namun Tuhan Yesus berkata bahwa jika kita memiliki iman sebesar biji sesawi, kita dapat berkata kepada gunung itu untuk berpindah.</p><p>Iman bukan sekadar percaya bahwa Tuhan ada, tetapi iman adalah kepercayaan penuh bahwa Tuhan mampu dan mau bertindak dalam situasi kita. Iman sejati diwujudkan dalam tindakan nyata — kita berdoa, kita melangkah, dan kita mempercayai Tuhan untuk hasilnya.</p><p>Hari ini, marilah kita periksa iman kita. Apakah kita sungguh-sungguh mempercayai Tuhan dalam setiap aspek hidup kita?</p>',
                'ayat_pendukung' => 'Matius 17:20',
                'gambar'         => null,
                'status'         => 'approved',
                'user_id'        => 2,
                'approved_at'    => now()->subDays(2),
                'approved_by'    => 1,
            ],
            [
                'judul'          => 'Kasih yang Tak Berkesudahan',
                'isi'            => '<p>Alkitab mengajarkan kita bahwa kasih adalah perintah terutama yang Tuhan berikan kepada kita. Mengasihi Tuhan dengan segenap hati, jiwa, dan akal budi — dan mengasihi sesama seperti diri sendiri.</p><p>Kasih bukan perasaan semata, tetapi kasih adalah pilihan dan tindakan. Ketika kita memilih untuk mengampuni, memilih untuk menolong, memilih untuk bersabar — itulah kasih yang nyata.</p>',
                'ayat_pendukung' => '1 Korintus 13:4-7',
                'gambar'         => null,
                'status'         => 'pending',
                'user_id'        => 3,
                'approved_at'    => null,
                'approved_by'    => null,
            ],
            [
                'judul'          => 'Damai Sejahtera dari Tuhan',
                'isi'            => '<p>Di tengah dunia yang penuh kekhawatiran dan ketidakpastian, Tuhan menawarkan sesuatu yang dunia tidak dapat berikan — damai sejahtera. Damai yang melampaui segala akal manusia.</p><p>Jangan khawatir akan apapun, tetapi nyatakanlah dalam segala hal keinginanmu kepada Allah dalam doa dan permohonan dengan ucapan syukur.</p>',
                'ayat_pendukung' => 'Filipi 4:6-7',
                'gambar'         => null,
                'status'         => 'approved',
                'user_id'        => 2,
                'approved_at'    => now()->subDays(5),
                'approved_by'    => 1,
            ],
        ];

        foreach ($devotions as $devotion) {
            Devotion::create($devotion);
        }
    }
}
