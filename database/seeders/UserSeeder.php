<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = ['Admin', 'Dzaki', 'Venna', 'Wulan', 'Devi', 'Vania', 'Karel', 'Romi', 'Denise', 'Nadya', 'Salwa', 'Kaila', 'Hildan', 'Naiya', 'Rifky'];

        foreach ($names as $name) {
            User::factory()->create([
                'name' => $name,
                'email' => $name . '@ciptamuri.co.id',
                'password' => '12345',
            ]);
        }
    }
}
