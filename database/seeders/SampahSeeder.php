<?php

namespace Database\Seeders;

use App\Models\Sampah; // Import model Sampah
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SampahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan ada user di database, jika tidak, buat satu
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }

        $sampahData = [
            ['jenis_sampah' => 'Plastik PET (botol minuman)', 'saldo_per_kg' => rand(1500, 2500), 'poin_per_kg' => rand(10, 20)],
            ['jenis_sampah' => 'Kertas HVS', 'saldo_per_kg' => rand(500, 1500), 'poin_per_kg' => rand(5, 15)],
            ['jenis_sampah' => 'Kardus', 'saldo_per_kg' => rand(400, 1200), 'poin_per_kg' => rand(4, 12)],
            ['jenis_sampah' => 'Logam (kaleng minuman)', 'saldo_per_kg' => rand(800, 2000), 'poin_per_kg' => rand(8, 18)],
            ['jenis_sampah' => 'Kaca (botol)', 'saldo_per_kg' => rand(300, 1000), 'poin_per_kg' => rand(3, 10)],
        ];

        foreach ($sampahData as $data) {
            Sampah::create([
                'id' => Str::ulid(), // Generate ULID for the 'id' field
                'user_id' => $user->id, // Assign user_id
                'jenis_sampah' => $data['jenis_sampah'],
                'saldo_per_kg' => $data['saldo_per_kg'],
                'poin_per_kg' => $data['poin_per_kg'],
            ]);
        }
    }
}