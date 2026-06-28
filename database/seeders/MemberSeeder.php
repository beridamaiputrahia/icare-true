<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{
    public function run(): void
    {
        $members = [
            ['nama_lengkap' => 'Samuel Wijaya',    'nama_panggilan' => 'Samuel',  'nomor_hp' => '08121234001', 'tanggal_lahir' => '1985-03-15', 'covenant_number' => 'ICT-001', 'alamat' => 'Jl. Merdeka No. 1, Ungaran'],
            ['nama_lengkap' => 'Maria Santoso',    'nama_panggilan' => 'Maria',   'nomor_hp' => '08121234002', 'tanggal_lahir' => '1990-07-22', 'covenant_number' => 'ICT-002', 'alamat' => 'Jl. Pahlawan No. 5, Ungaran'],
            ['nama_lengkap' => 'Andreas Kurniawan','nama_panggilan' => 'Andreas', 'nomor_hp' => '08121234003', 'tanggal_lahir' => '1988-11-08', 'covenant_number' => 'ICT-003', 'alamat' => 'Jl. Veteran No. 12, Semarang'],
            ['nama_lengkap' => 'Grace Lestari',   'nama_panggilan' => 'Grace',   'nomor_hp' => '08121234004', 'tanggal_lahir' => '1995-01-30', 'covenant_number' => 'ICT-004', 'alamat' => 'Jl. Diponegoro No. 3, Ungaran'],
            ['nama_lengkap' => 'Budi Hartono',     'nama_panggilan' => 'Budi',    'nomor_hp' => '08121234005', 'tanggal_lahir' => '1982-06-17', 'covenant_number' => 'ICT-005', 'alamat' => 'Jl. Ahmad Yani No. 8, Ungaran'],
            ['nama_lengkap' => 'Yohanes Susanto',  'nama_panggilan' => 'Yohan',   'nomor_hp' => '08121234006', 'tanggal_lahir' => '1993-09-25', 'covenant_number' => 'ICT-006', 'alamat' => 'Jl. Sudirman No. 20, Semarang'],
            ['nama_lengkap' => 'Ruth Wibowo',      'nama_panggilan' => 'Ruth',    'nomor_hp' => '08121234007', 'tanggal_lahir' => '1998-12-04', 'covenant_number' => 'ICT-007', 'alamat' => 'Jl. Gajahmada No. 15, Ungaran'],
            ['nama_lengkap' => 'Daniel Prasetyo',  'nama_panggilan' => 'Daniel',  'nomor_hp' => '08121234008', 'tanggal_lahir' => '1987-04-12', 'covenant_number' => 'ICT-008', 'alamat' => 'Jl. Imam Bonjol No. 6, Semarang'],
            ['nama_lengkap' => 'Naomi Halim',      'nama_panggilan' => 'Naomi',   'nomor_hp' => '08121234009', 'tanggal_lahir' => '2000-08-19', 'covenant_number' => 'ICT-009', 'alamat' => 'Jl. S. Parman No. 33, Ungaran'],
            ['nama_lengkap' => 'Petrus Handoko',   'nama_panggilan' => 'Petrus',  'nomor_hp' => '08121234010', 'tanggal_lahir' => '1979-02-28', 'covenant_number' => 'ICT-010', 'alamat' => 'Jl. MT. Haryono No. 9, Semarang'],
        ];

        foreach ($members as $data) {
            Member::create(array_merge($data, [
                'is_active'  => true,
                'created_by' => 1,
            ]));
        }
    }
}
