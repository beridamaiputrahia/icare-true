<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ── Tenant 1: I Care True Church ──────────────────────────────
        $tenant1 = Tenant::create([
            'nama_perusahaan' => 'I Care True Church',
            'slug'            => 'icaretrue',
            'email'           => 'admin@icaretrue.com',
            'is_active'       => true,
        ]);

        // Buat setting default untuk tenant 1
        (new AppSettingSeeder)->run($tenant1->id, 'I Care True Church');

        User::create([
            'name'      => 'Administrator',
            'email'     => 'admin@icaretrue.com',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'is_active' => true,
            'tenant_id' => $tenant1->id,
        ]);

        User::create([
            'name'      => 'John Doe',
            'email'     => 'user@icaretrue.com',
            'password'  => Hash::make('password'),
            'role'      => 'anggota',
            'is_active' => true,
            'tenant_id' => $tenant1->id,
        ]);

        User::create([
            'name'      => 'Maria Santoso',
            'email'     => 'maria@icaretrue.com',
            'password'  => Hash::make('password'),
            'role'      => 'anggota',
            'is_active' => true,
            'tenant_id' => $tenant1->id,
        ]);

        // ── Tenant 2: Grace Community (contoh tenant kedua) ───────────
        $tenant2 = Tenant::create([
            'nama_perusahaan' => 'Grace Community',
            'slug'            => 'grace',
            'email'           => 'admin@grace.com',
            'is_active'       => true,
        ]);

        // Buat setting default untuk tenant 2 (nama app berbeda)
        (new AppSettingSeeder)->run($tenant2->id, 'Grace Community');

        User::create([
            'name'      => 'Grace Admin',
            'email'     => 'admin@grace.com',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'is_active' => true,
            'tenant_id' => $tenant2->id,
        ]);
    }
}
