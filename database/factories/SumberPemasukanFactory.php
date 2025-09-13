<?php

namespace Database\Factories;

use App\Models\SumberPemasukan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SumberPemasukan>
 */
class SumberPemasukanFactory extends Factory
{
    protected $model = SumberPemasukan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $sumberOptions = [
            'Wisata Edukasi',
            'Perlombaan/Kegiatan',
            'Donasi',
            'Penjualan Sampah',
            'Workshop Lingkungan',
            'Training Program',
            'Konsultasi Lingkungan',
            'Produk Daur Ulang',
            'Iuran Anggota',
            'Sponsor Kegiatan',
            'Grant Pemerintah',
            'CSR Perusahaan',
            'Kemitraan Sekolah',
            'Program Bank Induk',
            'Hasil Investasi',
        ];

        return [
            'nama_pemasukan' => fake()->unique()->randomElement($sumberOptions),
        ];
    }

    /**
     * Create waste-related income source.
     */
    public function waste(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_pemasukan' => fake()->randomElement([
                'Penjualan Sampah',
                'Produk Daur Ulang',
                'Hasil Olahan Sampah',
            ]),
        ]);
    }

    /**
     * Create educational income source.
     */
    public function educational(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_pemasukan' => fake()->randomElement([
                'Wisata Edukasi',
                'Workshop Lingkungan',
                'Training Program',
                'Konsultasi Lingkungan',
            ]),
        ]);
    }

    /**
     * Create event income source.
     */
    public function event(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_pemasukan' => fake()->randomElement([
                'Perlombaan/Kegiatan',
                'Sponsor Kegiatan',
                'Event Fundraising',
            ]),
        ]);
    }

    /**
     * Create partnership income source.
     */
    public function partnership(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama_pemasukan' => fake()->randomElement([
                'CSR Perusahaan',
                'Kemitraan Sekolah',
                'Program Bank Induk',
                'Grant Pemerintah',
            ]),
        ]);
    }
}