<?php

namespace Database\Factories;

use App\Models\Pemasukan;
use App\Models\Rekening;
use App\Models\User;
use App\Models\SumberPemasukan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pemasukan>
 */
class PemasukanFactory extends Factory
{
    protected $model = Pemasukan::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $metodePembayaran = ['Tunai', 'Transfer', 'Sampah'];
        $metode = fake()->randomElement($metodePembayaran);

        // Nominal berdasarkan metode pembayaran
        $nominal = match($metode) {
            'Sampah' => fake()->randomFloat(2, 5000, 100000),    // Dari penjualan sampah
            'Transfer' => fake()->randomFloat(2, 100000, 1000000), // Transfer besar
            'Tunai' => fake()->randomFloat(2, 10000, 500000),    // Tunai sedang
            default => fake()->randomFloat(2, 10000, 500000),
        };

        $keterangan = match($metode) {
            'Sampah' => fake()->randomElement([
                'Penjualan sampah plastik ke pengepul',
                'Penjualan kardus ke pabrik',
                'Penjualan logam bekas',
                'Hasil penjualan sampah campuran',
                'Income dari bank sampah'
            ]),
            'Transfer' => fake()->randomElement([
                'Dana hibah pemerintah',
                'Bantuan CSR perusahaan',
                'Transfer dari bank sampah induk',
                'Dana program lingkungan',
                'Bantuan operasional'
            ]),
            'Tunai' => fake()->randomElement([
                'Donasi dari masyarakat',
                'Iuran bulanan nasabah',
                'Hasil kegiatan wisata edukasi',
                'Pendapatan dari workshop',
                'Sumbangan sukarela'
            ]),
            default => 'Pemasukan lainnya',
        };

        return [
            'nominal' => $nominal,
            'keterangan' => $keterangan,
            'metode_pembayaran' => $metode,
            'tanggal_pemasukan' => fake()->dateTimeBetween('-1 year', 'now'),
            'sumber_pemasukan_id' => SumberPemasukan::factory(),
            'rekening_id' => null, // Akan diset untuk non-sampah
            'user_id' => User::factory(),
        ];
    }

    /**
     * Create income from waste sales.
     */
    public function fromWaste(): static
    {
        return $this->state(fn (array $attributes) => [
            'metode_pembayaran' => 'Sampah',
            'nominal' => fake()->randomFloat(2, 10000, 200000),
            'keterangan' => fake()->randomElement([
                'Penjualan sampah plastik ke pengepul',
                'Penjualan kardus ke pabrik',
                'Penjualan logam bekas',
                'Hasil penjualan sampah campuran',
            ]),
            'rekening_id' => null, // Pemasukan dari sampah tidak terkait rekening nasabah
        ]);
    }

    /**
     * Create cash income.
     */
    public function cash(): static
    {
        return $this->state(function (array $attributes) {
            // Set rekening to donation account for cash income
            $donationRekening = Rekening::where('no_rekening', '00000000')->first();
            
            return [
                'metode_pembayaran' => 'Tunai',
                'nominal' => fake()->randomFloat(2, 5000, 100000),
                'keterangan' => fake()->randomElement([
                    'Donasi dari masyarakat',
                    'Hasil kegiatan wisata edukasi',
                    'Pendapatan dari workshop',
                    'Sumbangan sukarela',
                ]),
                'rekening_id' => $donationRekening?->id,
            ];
        });
    }

    /**
     * Create transfer income.
     */
    public function transfer(): static
    {
        return $this->state(function (array $attributes) {
            // Set rekening to donation account for transfer income
            $donationRekening = Rekening::where('no_rekening', '00000000')->first();
            
            return [
                'metode_pembayaran' => 'Transfer',
                'nominal' => fake()->randomFloat(2, 100000, 2000000),
                'keterangan' => fake()->randomElement([
                    'Dana hibah pemerintah',
                    'Bantuan CSR perusahaan',
                    'Transfer dari bank sampah induk',
                    'Dana program lingkungan',
                ]),
                'rekening_id' => $donationRekening?->id,
            ];
        });
    }

    /**
     * Create large income.
     */
    public function large(): static
    {
        return $this->state(fn (array $attributes) => [
            'nominal' => fake()->randomFloat(2, 500000, 5000000),
            'keterangan' => 'Pemasukan besar - ' . fake()->sentence(),
        ]);
    }

    /**
     * Create small income.
     */
    public function small(): static
    {
        return $this->state(fn (array $attributes) => [
            'nominal' => fake()->randomFloat(2, 1000, 25000),
            'keterangan' => 'Pemasukan kecil - ' . fake()->sentence(),
        ]);
    }

    /**
     * Create recent income.
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'tanggal_pemasukan' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }
}