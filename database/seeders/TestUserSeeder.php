<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test users for login testing
        $testUsers = [
            [
                'name' => 'Admin Cipta Muri',
                'nik' => '1234567890123456',
                'email' => 'admin@ciptamuri.com',
                'password' => Hash::make('password123'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Budi Santoso',
                'nik' => '3201234567890123',
                'email' => 'budi@example.com',
                'password' => Hash::make('budi123'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Siti Nurhaliza',
                'nik' => '3301234567890456',
                'email' => 'siti@example.com',
                'password' => Hash::make('siti123'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Ahmad Rahman',
                'nik' => '3501234567890789',
                'email' => 'ahmad@example.com',
                'password' => Hash::make('ahmad123'),
                'email_verified_at' => now(),
            ],
        ];

        foreach ($testUsers as $userData) {
            User::updateOrCreate(
                ['nik' => $userData['nik']], // Check by NIK
                $userData
            );
        }

        $this->command->info('Test users created successfully!');
        $this->command->table(
            ['Name', 'NIK', 'Email', 'Password'],
            [
                ['Admin Cipta Muri', '1234567890123456', 'admin@ciptamuri.com', 'password123'],
                ['Budi Santoso', '3201234567890123', 'budi@example.com', 'budi123'],
                ['Siti Nurhaliza', '3301234567890456', 'siti@example.com', 'siti123'],
                ['Ahmad Rahman', '3501234567890789', 'ahmad@example.com', 'ahmad123'],
            ]
        );
    }
}
