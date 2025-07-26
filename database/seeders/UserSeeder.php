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
        $names = ['admin', 'dzaki', 'venna', 'wulan', 'devi', 'vania', 'karel', 'romi', 'denise', 'nadya', 'salwa', 'kaila', 'hildan', 'naiya', 'rifky'];

        foreach ($names as $name) {
            User::factory()->create([
                'name' => $name,
                'email' => $name . '@example.com',
                'password' => '12345',
            ]);
        }
    }
}
