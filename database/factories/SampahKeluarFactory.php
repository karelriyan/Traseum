<?php

namespace Database\Factories;

use App\Models\SampahKeluar;
use App\Models\Sampah;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SampahKeluar>
 */
class SampahKeluarFactory extends Factory
{
    protected $model = SampahKeluar::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $keteranganOptions = [
            'Dijual ke pengepul plastik',
            'Dikirim ke pabrik daur ulang',
            'Diserahkan ke bank sampah induk',
            'Dijual ke lapak terdekat',
            'Didonasikan untuk kegiatan lingkungan',
            'Diserahkan ke PT. Recycle Indonesia',
            'Dijual ke collector regional',
            'Dikirim ke fasilitas pengolahan',
            'Diserahkan ke mitra pengolah sampah',
            'Dijual melalui tender pengadaan',
        ];

        return [
            'sampah_id' => Sampah::factory(),
            'berat_keluar' => fake()->randomFloat(2, 10, 500), // 10kg - 500kg
            'tanggal_keluar' => fake()->dateTimeBetween('-6 months', 'now'),
            'keterangan' => fake()->randomElement($keteranganOptions),
            'user_id' => User::factory(),
        ];
    }

    /**
     * Create large scale exit.
     */
    public function large(): static
    {
        return $this->state(fn (array $attributes) => [
            'berat_keluar' => fake()->randomFloat(2, 200, 1000),
            'keterangan' => fake()->randomElement([
                'Dijual ke pabrik daur ulang',
                'Diserahkan ke bank sampah induk',
                'Dikirim ke fasilitas pengolahan',
                'Dijual melalui tender pengadaan',
            ]),
        ]);
    }

    /**
     * Create small scale exit.
     */
    public function small(): static
    {
        return $this->state(fn (array $attributes) => [
            'berat_keluar' => fake()->randomFloat(2, 5, 50),
            'keterangan' => fake()->randomElement([
                'Dijual ke lapak terdekat',
                'Diserahkan ke collector lokal',
                'Didonasikan untuk kegiatan lingkungan',
            ]),
        ]);
    }

    /**
     * Create plastic waste exit.
     */
    public function plastic(): static
    {
        return $this->state(fn (array $attributes) => [
            'sampah_id' => Sampah::factory()->plastic(),
            'keterangan' => 'Dijual ke pengepul plastik',
        ]);
    }

    /**
     * Create paper waste exit.
     */
    public function paper(): static
    {
        return $this->state(fn (array $attributes) => [
            'sampah_id' => Sampah::factory()->paper(),
            'keterangan' => 'Dikirim ke pabrik kertas daur ulang',
        ]);
    }

    /**
     * Create metal waste exit.
     */
    public function metal(): static
    {
        return $this->state(fn (array $attributes) => [
            'sampah_id' => Sampah::factory()->metal(),
            'keterangan' => 'Dijual ke pengepul logam',
        ]);
    }

    /**
     * Create recent exit (within last month).
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'tanggal_keluar' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }

    /**
     * Create old exit (more than 3 months ago).
     */
    public function old(): static
    {
        return $this->state(fn (array $attributes) => [
            'tanggal_keluar' => fake()->dateTimeBetween('-1 year', '-3 months'),
        ]);
    }
}