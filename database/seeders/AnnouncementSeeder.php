<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        $announcements = [
            [
                'judul'        => 'Pendaftaran Baptisan Air Gelombang III',
                'isi'          => '<p>Shalom! Kami membuka pendaftaran baptisan air gelombang III tahun ini. Baptisan akan dilaksanakan pada hari Minggu, 15 Juli 2026.</p><p><strong>Syarat pendaftaran:</strong></p><ul><li>Sudah menerima Yesus sebagai Tuhan dan Juruselamat</li><li>Mengikuti kelas persiapan baptisan (3 sesi)</li><li>Mengisi formulir pendaftaran</li></ul><p>Hubungi sekretariat gereja untuk informasi lebih lanjut.</p>',
                'gambar'       => null,
                'penulis'      => 'Sekretariat Gereja',
                'is_published' => true,
                'created_by'   => 1,
            ],
            [
                'judul'        => 'Jadwal Pelayanan Bulan Juli 2026',
                'isi'          => '<p>Kepada seluruh tim pelayanan, berikut adalah jadwal pelayanan untuk bulan Juli 2026. Harap konfirmasi kehadiran Anda kepada koordinator masing-masing divisi.</p><p>Jadwal lengkap dapat diunduh melalui link yang tersedia.</p>',
                'gambar'       => null,
                'penulis'      => 'Tim Koordinator',
                'is_published' => true,
                'created_by'   => 1,
            ],
            [
                'judul'        => 'Konser Pujian "Malam Penyembahan"',
                'isi'          => '<p>Bergabunglah bersama kami dalam Konser Pujian "Malam Penyembahan" yang akan diadakan pada:</p><p><strong>Tanggal:</strong> Sabtu, 20 Juli 2026<br><strong>Waktu:</strong> 18.00 - 21.00 WIB<br><strong>Tempat:</strong> Gedung Serbaguna Ungaran</p><p>Acara ini gratis dan terbuka untuk umum. Ajak keluarga dan sahabat Anda!</p>',
                'gambar'       => null,
                'penulis'      => 'Panitia Konser',
                'is_published' => true,
                'created_by'   => 1,
            ],
        ];

        foreach ($announcements as $announcement) {
            Announcement::create($announcement);
        }
    }
}
