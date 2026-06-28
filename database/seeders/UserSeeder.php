<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name'     => 'Administrator',
            'email'    => 'admin@icaretrue.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
            'is_active' => true,
        ]);

        User::create([
            'name'     => 'John Doe',
            'email'    => 'user@icaretrue.com',
            'password' => Hash::make('password'),
            'role'     => 'user',
            'is_active' => true,
        ]);

        User::create([
            'name'     => 'Maria Santoso',
            'email'    => 'maria@icaretrue.com',
            'password' => Hash::make('password'),
            'role'     => 'user',
            'is_active' => true,
        ]);
    }
}
