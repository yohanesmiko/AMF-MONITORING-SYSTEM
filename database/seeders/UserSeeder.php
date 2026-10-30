<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ===== ADMIN =====
        User::updateOrCreate(
            ['email' => 'admin@atmi.com'],
            [
                'name' => 'Mikael Fortuna',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'jabatan' => 'Manager Produksi',
                'shift' => null,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // ===== OPERATOR SHIFT 1 =====
        User::updateOrCreate(
            ['email' => 'operator1@atmi.com'],
            [
                'name' => 'Budi Santoso',
                'password' => Hash::make('password123'),
                'role' => 'operator',
                'jabatan' => 'Operator Mesin',
                'shift' => '1',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // ===== OPERATOR SHIFT 2 =====
        User::updateOrCreate(
            ['email' => 'operator2@atmi.com'],
            [
                'name' => 'Andi Wijaya',
                'password' => Hash::make('password123'),
                'role' => 'operator',
                'jabatan' => 'Operator Mesin',
                'shift' => '2',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // ===== OPERATOR SHIFT 3 =====
        User::updateOrCreate(
            ['email' => 'operator3@atmi.com'],
            [
                'name' => 'Dedi Kurniawan',
                'password' => Hash::make('password123'),
                'role' => 'operator',
                'jabatan' => 'Operator Mesin',
                'shift' => '3',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ User berhasil dibuat!');
        $this->command->info('   Admin: admin@atmi.com / password123');
        $this->command->info('   Operator 1: operator1@atmi.com / password123');
        $this->command->info('   Operator 2: operator2@atmi.com / password123');
        $this->command->info('   Operator 3: operator3@atmi.com / password123');
    }
}