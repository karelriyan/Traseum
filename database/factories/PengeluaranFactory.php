<?php

namespace Database\Factories;

use App\Models\Pengeluaran;
use App\Models\Rekening;
use App\Models\User;
use App\Models\KategoriPengeluaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pengeluaran>
 */
class PengeluaranFactory extends Factory
{
    protected $model = Pengeluaran::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $keteranganOptions = [
            // Rutin
            'Gaji koordinator bank sampah',
            'Listrik gudang penyimpanan',
            'Biaya operasional bulanan',
            'Maintenance peralatan',
            'Transport pengambilan sampah',
            
            // Kebutuhan
            'Pembelian timbangan digital',
            'Karung plastik untuk sampah',
            'Alat tulis kantor',
            'Seragam petugas',
            'Masker dan sarung tangan',
            
            // Perlombaan/Kegiatan
            'Hadiah lomba lingkungan',
            'Konsumsi workshop',
            'Sewa sound system',
            'Spanduk kegiatan',
            'Transport narasumber',
            
            // Donasi
            'Bantuan untuk warga kurang mampu',
            'Donasi untuk sekolah',
            'Bantuan korban bencana',
            'Program beasiswa',
            'Santunan yatim piatu',
        ];

        $metodePembayaran = ['Tunai', 'Transfer'];
        $metode = fake()->randomElement($metodePembayaran);

        // Nominal berdasarkan metode pembayaran
        $nominal = match($metode) {
            'Transfer' => fake()->randomFloat(2, 100000, 2000000), // Transfer untuk nominal besar
            'Tunai' => fake()->randomFloat(2, 5000, 500000),       // Tunai untuk nominal kecil-sedang
            default => fake()->randomFloat(2, 10000, 500000),
        };

        return [
            'nominal' => $nominal,
            'keterangan' => fake()->randomElement($keteranganOptions),
            'metode_pembayaran' => $metode,
            'tanggal_pengeluaran' => fake()->dateTimeBetween('-1 year', 'now'),
            'kategori_pengeluaran_id' => KategoriPengeluaran::factory(),
            'rekening_id' => Rekening::factory(),
            'user_id' => User::factory(),
        ];
    }

    /**
     * Create routine expense.
     */
    public function routine(): static
    {
        return $this->state(fn (array $attributes) => [
            'nominal' => fake()->randomFloat(2, 100000, 1000000),
            'keterangan' => fake()->randomElement([
                'Gaji koordinator bank sampah',
                'Listrik gudang penyimpanan',
                'Biaya operasional bulanan',
                'Maintenance peralatan',
                'Transport pengambilan sampah',
            ]),
        ]);
    }

    /**
     * Create equipment/needs expense.
     */
    public function equipment(): static
    {
        return $this->state(fn (array $attributes) => [
            'nominal' => fake()->randomFloat(2, 50000, 500000),
            'keterangan' => fake()->randomElement([
                'Pembelian timbangan digital',
                'Karung plastik untuk sampah',
                'Alat tulis kantor',
                'Seragam petugas',
                'Masker dan sarung tangan',
            ]),
        ]);
    }

    /**
     * Create event/competition expense.
     */
    public function event(): static
    {
        return $this->state(fn (array $attributes) => [
            'nominal' => fake()->randomFloat(2, 200000, 2000000),
            'keterangan' => fake()->randomElement([
                'Hadiah lomba lingkungan',
                'Konsumsi workshop',
                'Sewa sound system',
                'Spanduk kegiatan',
                'Transport narasumber',
            ]),
        ]);
    }

    /**
     * Create donation expense.
     */
    public function donation(): static
    {
        return $this->state(fn (array $attributes) => [
            'nominal' => fake()->randomFloat(2, 100000, 1500000),
            'keterangan' => fake()->randomElement([
                'Bantuan untuk warga kurang mampu',
                'Donasi untuk sekolah',
                'Bantuan korban bencana',
                'Program beasiswa',
                'Santunan yatim piatu',
            ]),
        ]);
    }

    /**
     * Create cash expense.
     */
    public function cash(): static
    {
        return $this->state(fn (array $attributes) => [
            'metode_pembayaran' => 'Tunai',
            'nominal' => fake()->randomFloat(2, 5000, 200000),
        ]);
    }

    /**
     * Create transfer expense.
     */
    public function transfer(): static
    {
        return $this->state(fn (array $attributes) => [
            'metode_pembayaran' => 'Transfer',
            'nominal' => fake()->randomFloat(2, 100000, 3000000),
        ]);
    }

    /**
     * Create large expense.
     */
    public function large(): static
    {
        return $this->state(fn (array $attributes) => [
            'nominal' => fake()->randomFloat(2, 1000000, 5000000),
            'keterangan' => 'Pengeluaran besar - ' . fake()->sentence(),
        ]);
    }

    /**
     * Create small expense.
     */
    public function small(): static
    {
        return $this->state(fn (array $attributes) => [
            'nominal' => fake()->randomFloat(2, 1000, 50000),
            'keterangan' => 'Pengeluaran kecil - ' . fake()->sentence(),
        ]);
    }

    /**
     * Create recent expense.
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'tanggal_pengeluaran' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }
}