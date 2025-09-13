<?php

namespace Database\Factories;

use App\Models\SampahSummary;
use App\Models\Sampah;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SampahSummary>
 */
class SampahSummaryFactory extends Factory
{
    protected $model = SampahSummary::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tanggalSummary = fake()->dateTimeBetween('-1 year', 'now');
        
        // Generate realistic data based on waste type
        $beratMasuk = fake()->randomFloat(2, 5, 200); // 5kg - 200kg masuk
        $beratKeluar = fake()->randomFloat(2, 0, $beratMasuk * 0.8); // keluar maksimal 80% dari masuk

        return [
            'sampah_id' => Sampah::factory(),
            'tanggal_summary' => $tanggalSummary,
            'total_berat_masuk' => $beratMasuk,
            'total_berat_keluar' => $beratKeluar,
            'user_id' => User::factory(),
        ];
    }

    /**
     * Create summary for plastic waste.
     */
    public function plastic(): static
    {
        return $this->state(fn (array $attributes) => [
            'sampah_id' => Sampah::factory()->plastic(),
            'total_berat_masuk' => fake()->randomFloat(2, 20, 150), // Plastik volume tinggi
            'total_berat_keluar' => fake()->randomFloat(2, 15, 120),
        ]);
    }

    /**
     * Create summary for paper waste.
     */
    public function paper(): static
    {
        return $this->state(fn (array $attributes) => [
            'sampah_id' => Sampah::factory()->paper(),
            'total_berat_masuk' => fake()->randomFloat(2, 30, 200), // Kertas volume sedang-tinggi
            'total_berat_keluar' => fake()->randomFloat(2, 25, 180),
        ]);
    }

    /**
     * Create summary for metal waste.
     */
    public function metal(): static
    {
        return $this->state(fn (array $attributes) => [
            'sampah_id' => Sampah::factory()->metal(),
            'total_berat_masuk' => fake()->randomFloat(2, 5, 50), // Logam volume rendah tapi berat
            'total_berat_keluar' => fake()->randomFloat(2, 3, 45),
        ]);
    }

    /**
     * Create summary with high turnover (berat keluar tinggi).
     */
    public function highTurnover(): static
    {
        return $this->state(function (array $attributes) {
            $beratMasuk = fake()->randomFloat(2, 50, 300);
            $beratKeluar = $beratMasuk * fake()->randomFloat(2, 0.7, 0.95); // 70-95% keluar

            return [
                'total_berat_masuk' => $beratMasuk,
                'total_berat_keluar' => $beratKeluar,
            ];
        });
    }

    /**
     * Create summary with low turnover (berat keluar rendah).
     */
    public function lowTurnover(): static
    {
        return $this->state(function (array $attributes) {
            $beratMasuk = fake()->randomFloat(2, 20, 100);
            $beratKeluar = $beratMasuk * fake()->randomFloat(2, 0.1, 0.4); // 10-40% keluar

            return [
                'total_berat_masuk' => $beratMasuk,
                'total_berat_keluar' => $beratKeluar,
            ];
        });
    }

    /**
     * Create summary for current month.
     */
    public function currentMonth(): static
    {
        return $this->state(fn (array $attributes) => [
            'tanggal_summary' => fake()->dateTimeBetween('first day of this month', 'now'),
        ]);
    }

    /**
     * Create summary for last month.
     */
    public function lastMonth(): static
    {
        return $this->state(fn (array $attributes) => [
            'tanggal_summary' => fake()->dateTimeBetween('first day of last month', 'last day of last month'),
        ]);
    }

    /**
     * Create summary with no outflow (stok tertahan).
     */
    public function noOutflow(): static
    {
        return $this->state(fn (array $attributes) => [
            'total_berat_keluar' => 0,
            'total_berat_masuk' => fake()->randomFloat(2, 10, 100),
        ]);
    }

    /**
     * Create weekly summary.
     */
    public function weekly(): static
    {
        return $this->state(fn (array $attributes) => [
            'tanggal_summary' => fake()->dateTimeBetween('-1 week', 'now'),
            'total_berat_masuk' => fake()->randomFloat(2, 5, 50), // Volume mingguan lebih kecil
            'total_berat_keluar' => fake()->randomFloat(2, 2, 40),
        ]);
    }

    /**
     * Create daily summary.
     */
    public function daily(): static
    {
        return $this->state(fn (array $attributes) => [
            'tanggal_summary' => fake()->dateTimeBetween('-3 days', 'now'),
            'total_berat_masuk' => fake()->randomFloat(2, 1, 20), // Volume harian kecil
            'total_berat_keluar' => fake()->randomFloat(2, 0, 15),
        ]);
    }
}