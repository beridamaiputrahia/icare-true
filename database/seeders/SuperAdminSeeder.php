<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Akun superadmin pertama — tidak terikat tenant manapun (tenant_id null).
 * GANTI password default ini segera setelah login pertama kali.
 */
class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'superadmin@icaretrue.com'],
            [
                'name'      => 'Super Administrator',
                'password'  => Hash::make('password'),
                'role'      => User::ROLE_SUPERADMIN,
                'is_active' => true,
                'tenant_id' => null,
            ]
        );
    }
}
