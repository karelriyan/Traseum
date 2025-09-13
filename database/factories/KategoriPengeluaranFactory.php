<?php

namespace Database\Factories;

use App\Models\KategoriPengeluaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\KategoriPengeluaran>
 */
class KategoriPengeluaranFactory extends Factory
{
    protected $model = KategoriPengeluaran::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kategoriOptions = [
            'Rutin',
            'Kebutuhan',
            'Perlombaan/Kegiatan', 
            'Donasi',
            'Operasional',
            'Pemeliharaan',
            'Investasi',
            'Emergenci',
            'Administrasi',
            'Transport',
            'Konsumsi',
            'Peralatan',
            'Promosi',
            'Pelatihan',
            'Akomodasi',
        ];

        return [
            'nama_pengeluaran' => fake()->unique()->randomElement($kategoriOptions),
        ];
    }

    /**
     * Create routine expense category.
     */
    public function routine(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_pengeluaran' => fake()->randomElement([
                'Rutin',
                'Operasional',
                'Administrasi',
            ]),
        ]);
    }

    /**
     * Create event expense category.
     */
    public function event(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_pengeluaran' => fake()->randomElement([
                'Perlombaan/Kegiatan',
                'Promosi',
                'Konsumsi',
                'Akomodasi',
            ]),
        ]);
    }

    /**
     * Create equipment expense category.
     */
    public function equipment(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_pengeluaran' => fake()->randomElement([
                'Kebutuhan',
                'Peralatan',
                'Investasi',
                'Pemeliharaan',
            ]),
        ]);
    }

    /**
     * Create social expense category.
     */
    public function social(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_pengeluaran' => fake()->randomElement([
                'Donasi',
                'Bantuan Sosial',
                'Program Kemasyarakatan',
            ]),
        ]);
    }
}