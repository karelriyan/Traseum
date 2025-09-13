<?php

namespace Database\Factories;

use App\Models\Sampah;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sampah>
 */
class SampahFactory extends Factory
{
    protected $model = Sampah::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jenisSampah = [
            'Plastik PET (botol minuman)',
            'Plastik HDPE (botol detergen)',
            'Plastik PP (tutup botol)',
            'Plastik PS (styrofoam)',
            'Plastik campuran',
            'Kertas HVS',
            'Kertas koran',
            'Kertas majalah',
            'Kertas duplex',
            'Kardus',
            'Kardus tebal',
            'Logam aluminium (kaleng)',
            'Logam besi',
            'Logam tembaga',
            'Kaca bening (botol)',
            'Kaca warna (botol)',
            'Kaca pecah',
            'Elektronik (HP bekas)',
            'Elektronik (kabel)',
            'Tekstil (pakaian bekas)',
        ];

        $selectedJenis = fake()->unique()->randomElement($jenisSampah);

        // Harga berdasarkan jenis sampah (dalam rupiah per kg)
        $hargaMapping = [
            'Plastik PET (botol minuman)' => [1500, 2500],
            'Plastik HDPE (botol detergen)' => [1200, 2000],
            'Plastik PP (tutup botol)' => [800, 1500],
            'Plastik PS (styrofoam)' => [500, 1000],
            'Plastik campuran' => [300, 800],
            'Kertas HVS' => [500, 1500],
            'Kertas koran' => [300, 800],
            'Kertas majalah' => [400, 1000],
            'Kertas duplex' => [600, 1200],
            'Kardus' => [400, 1200],
            'Kardus tebal' => [800, 1500],
            'Logam aluminium (kaleng)' => [8000, 15000],
            'Logam besi' => [2000, 5000],
            'Logam tembaga' => [50000, 80000],
            'Kaca bening (botol)' => [300, 1000],
            'Kaca warna (botol)' => [200, 800],
            'Kaca pecah' => [100, 500],
            'Elektronik (HP bekas)' => [10000, 50000],
            'Elektronik (kabel)' => [15000, 30000],
            'Tekstil (pakaian bekas)' => [1000, 3000],
        ];

        $hargaRange = $hargaMapping[$selectedJenis] ?? [500, 2000];
        $saldoPerKg = fake()->numberBetween($hargaRange[0], $hargaRange[1]);
        
        // Poin per kg biasanya 1/100 dari harga
        $poinPerKg = max(1, intval($saldoPerKg / 100));

        return [
            'jenis_sampah' => $selectedJenis,
            'saldo_per_kg' => $saldoPerKg,
            'poin_per_kg' => $poinPerKg,
            'total_berat_terkumpul' => fake()->randomFloat(4, 0, 1000), // 0 - 1000 kg
            'user_id' => User::factory(),
        ];
    }

    /**
     * Create plastic waste type.
     */
    public function plastic(): static
    {
        $plasticTypes = [
            'Plastik PET (botol minuman)',
            'Plastik HDPE (botol detergen)',
            'Plastik PP (tutup botol)',
            'Plastik PS (styrofoam)',
            'Plastik campuran',
        ];

        return $this->state(fn (array $attributes) => [
            'jenis_sampah' => fake()->randomElement($plasticTypes),
            'saldo_per_kg' => fake()->numberBetween(500, 2500),
            'poin_per_kg' => fake()->numberBetween(5, 25),
        ]);
    }

    /**
     * Create paper waste type.
     */
    public function paper(): static
    {
        $paperTypes = [
            'Kertas HVS',
            'Kertas koran',
            'Kertas majalah',
            'Kertas duplex',
            'Kardus',
            'Kardus tebal',
        ];

        return $this->state(fn (array $attributes) => [
            'jenis_sampah' => fake()->randomElement($paperTypes),
            'saldo_per_kg' => fake()->numberBetween(300, 1500),
            'poin_per_kg' => fake()->numberBetween(3, 15),
        ]);
    }

    /**
     * Create metal waste type.
     */
    public function metal(): static
    {
        $metalTypes = [
            'Logam aluminium (kaleng)',
            'Logam besi',
            'Logam tembaga',
        ];

        return $this->state(fn (array $attributes) => [
            'jenis_sampah' => fake()->randomElement($metalTypes),
            'saldo_per_kg' => fake()->numberBetween(2000, 80000),
            'poin_per_kg' => fake()->numberBetween(20, 800),
        ]);
    }
}