<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Mikael Fortuna',
            'email' => 'admin@atmi.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '081234567890',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Staff Gudang',
            'email' => 'staff@atmi.com',
            'password' => Hash::make('password123'),
            'role' => 'staff',
            'phone' => '081234567891',
            'email_verified_at' => now(),
        ]);
    }
}