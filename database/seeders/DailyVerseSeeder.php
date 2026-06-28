<?php

namespace Database\Seeders;

use App\Models\DailyVerse;
use Illuminate\Database\Seeder;

class DailyVerseSeeder extends Seeder
{
    public function run(): void
    {
        DailyVerse::create([
            'ayat'             => '"Karena begitu besar kasih Allah akan dunia ini, sehingga Ia telah mengaruniakan Anak-Nya yang tunggal, supaya setiap orang yang percaya kepada-Nya tidak binasa, melainkan beroleh hidup yang kekal."',
            'referensi'        => 'Yohanes 3:16',
            'renungan_singkat' => 'Kasih Allah kepada kita tidak terbatas. Ia telah memberikan yang terbaik dan paling berharga — Anak-Nya sendiri — agar kita beroleh keselamatan. Renungkan betapa besarnya kasih itu dan bersyukurlah hari ini.',
            'tanggal'          => today(),
            'is_active'        => true,
            'created_by'       => 1,
        ]);

        DailyVerse::create([
            'ayat'             => '"Segala perkara dapat kutanggung di dalam Dia yang memberi kekuatan kepadaku."',
            'referensi'        => 'Filipi 4:13',
            'renungan_singkat' => 'Tidak ada situasi yang terlalu berat jika kita mengandalkan kekuatan dari Tuhan. Dalam setiap tantangan yang kita hadapi, ingatlah bahwa Tuhan adalah sumber kekuatan kita.',
            'tanggal'          => today()->subDay(),
            'is_active'        => true,
            'created_by'       => 1,
        ]);

        DailyVerse::create([
            'ayat'             => '"Tuhan adalah gembalaku, takkan kekurangan aku."',
            'referensi'        => 'Mazmur 23:1',
            'renungan_singkat' => 'Seperti seorang gembala yang merawat domba-dombanya, Tuhan pun merawat kita dengan penuh kasih. Percayakan hidupmu kepada-Nya dan Ia akan mencukupkan segala kebutuhanmu.',
            'tanggal'          => today()->addDay(),
            'is_active'        => true,
            'created_by'       => 1,
        ]);
    }
}
