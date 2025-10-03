<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Hexters\HexaLite\Models\HexaRole;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds
     */
    public function run(): void
    {
        // 1) Buat role jika belum ada
        $adminRole = HexaRole::where('name', 'Admin')->first();
        if (!$adminRole) {
            $adminRole = new HexaRole();
            $adminRole->name = 'Admin';
            $adminRole->guard = 'web';
            $adminRole->save();
        }

        $superRole = HexaRole::where('name', 'Super Admin')->first();
        if (!$superRole) {
            $superRole = new HexaRole();
            $superRole->name = 'Super Admin';
            $superRole->guard = 'web';
            $superRole->save();
        }

        // 2) Buat user superadmin
        $superUser = User::firstOrCreate(
            ['email' => 'superadmin@ciptamuri.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('123456789'),
                'email_verified_at' => now(),
            ]
        );

        // 3) Assign role Super Admin
        if (!$superUser->hasRole('Super Admin')) {
            $superUser->roles()->syncWithoutDetaching([$superRole->id]);
        }
        if (!$superUser->hasRole('Admin')) {
            $superUser->roles()->syncWithoutDetaching([$adminRole->id]);
        }

        // 4) Buat user admin biasa untuk testing
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@ciptamuri.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        // 5) Assign role Admin saja
        if (!$adminUser->hasRole('Admin')) {
            $adminUser->roles()->syncWithoutDetaching([$adminRole->id]);
        }

        $names = [
            // Nama Pelaksana
            'Dzaki Zain',
            'Venna Firena',
            'Nurhaliza Tri Wulandari',
            'Devi Mei Ningrum',
            'Vania Anindita Hartomo',
            'Karel Tsalasatir Riyan',
            "Muhammad As'ad Al Quroimi",
            'Denise Amanda',
            'Nadya Ulya Prasetyani',
            'Salwa An-Nida',
            'Kaila Tahta Aurelia',
            'Hildan Ardiansah',
            'Naiya Amelia',
            'Rifky Dwi Rahmat Prakoso',

            // Nama Selain Pelaksana
            'Netika Alifiyah',
            'Anisah Fatmawati',
            'Azzahra Nur Annisa',
            'Puan Anindya',
            'M. Kholid Ibrahim',
        ];

        foreach ($names as $name) {
            $parts = preg_split('/\s+/', $name); // pisahkan nama berdasarkan spasi
            $firstName = Str::lower(preg_replace('/[^a-zA-Z]/', '', $parts[0]));
            $lastName = Str::lower(preg_replace('/[^a-zA-Z]/', '', end($parts)));

            // Email: namadepan.namabelakang@ciptamuri.com
            $email = $firstName . '.' . $lastName . '@ciptamuri.com';

            // Password: nama depan + huruf terakhir nama depan -> angka
            $lastChar = substr($firstName, -1);
            $charToNum = ord(strtolower($lastChar)) - 96; // a=1, b=2, ... z=26
            $rawPassword = $firstName . $charToNum;

            // Gunakan firstOrCreate untuk menghindari duplicate
            $user = User::firstOrCreate(
                ['email' => $email], // cari berdasarkan email
                [
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make($rawPassword),
                    'email_verified_at' => now(),
                ]
            );

            // Assign role Admin untuk semua user selain super admin
            if (!$user->hasRole('Admin')) {
                $user->roles()->syncWithoutDetaching([$adminRole->id]);
            }

            // Pastikan tidak ada role Super Admin untuk user biasa
            if ($user->hasRole('Super Admin') && $user->email !== 'superadmin@ciptamuri.com') {
                $user->roles()->detach($superRole->id);
            }
        }
    }
}
