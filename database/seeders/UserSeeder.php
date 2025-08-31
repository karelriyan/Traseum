<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@ciptamuri.co.id',
            'password' => Hash::make('123456789'),
        ]);
        
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
            User::factory()->create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($rawPassword),
            ]);

            // Assign roles based on user
            if ($name === 'Admin') {
                $user->assignRole('admin');
            } elseif (in_array($name, ['Dzaki', 'Venna', 'Wulan'])) {
                $user->assignRole('petugas');
            } else {
                $user->assignRole('viewer');
            }
        }
    }
}
