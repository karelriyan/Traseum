<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds
     */
    public function run(): void
    {
         // 1) Buat role Super Admin (sekali saja)
        $superAdminRole = Role::firstOrCreate([
            'name' => 'Super Admin',     // gunakan nama persis ini
            'guard_name' => 'web',
        ]);

        // 2) Buat user superadmin
        $user = User::firstOrCreate(
            ['email' => 'superadmin@ciptamuri.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('123456789'),
            ]
        );

        // 3) Assign role ke user (ini yang dibaca Filament/Spatie)
        $user->assignRole($superAdminRole);

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

            User::factory()->create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($rawPassword),
            ]);
        }
    }
}
