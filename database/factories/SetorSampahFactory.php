<?php

namespace Database\Factories;

use App\Models\SetorSampah;
use App\Models\Rekening;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SetorSampah>
 */
class SetorSampahFactory extends Factory
{
    protected $model = SetorSampah::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jenisSetoran = ['rekening', 'donasi'];
        $selectedJenis = fake()->randomElement($jenisSetoran);

        return [
            'rekening_id' => Rekening::factory(),
            'jenis_setoran' => $selectedJenis,
            'berat' => fake()->randomFloat(4, 0.1, 50), // 0.1kg - 50kg
            'total_saldo_dihasilkan' => fake()->randomFloat(2, 1000, 100000), // 1k - 100k
            'total_poin_dihasilkan' => fake()->numberBetween(10, 1000),
            'calculation_performed' => fake()->boolean(80), // 80% sudah dihitung
            'user_id' => User::factory(),
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Create donation type setor sampah.
     */
    public function donation(): static
    {
        return $this->state(function (array $attributes) {
            // Find or create donation account
            $donationRekening = Rekening::where('no_rekening', '00000000')->first();
            if (!$donationRekening) {
                $donationRekening = Rekening::factory()->donation()->create();
            }

            return [
                'rekening_id' => $donationRekening->id,
                'jenis_setoran' => 'donasi',
                'total_saldo_dihasilkan' => 0, // Donasi tidak menghasilkan saldo
                'total_poin_dihasilkan' => fake()->numberBetween(50, 500), // Tetap dapat poin
            ];
        });
    }

    /**
     * Create regular account type setor sampah.
     */
    public function account(): static
    {
        return $this->state(fn (array $attributes) => [
            'jenis_setoran' => 'rekening',
            'total_saldo_dihasilkan' => fake()->randomFloat(2, 5000, 150000),
            'total_poin_dihasilkan' => fake()->numberBetween(50, 1500),
        ]);
    }

    /**
     * Create large transaction.
     */
    public function large(): static
    {
        return $this->state(fn (array $attributes) => [
            'berat' => fake()->randomFloat(4, 20, 100),
            'total_saldo_dihasilkan' => fake()->randomFloat(2, 50000, 500000),
            'total_poin_dihasilkan' => fake()->numberBetween(500, 5000),
        ]);
    }

    /**
     * Create small transaction.
     */
    public function small(): static
    {
        return $this->state(fn (array $attributes) => [
            'berat' => fake()->randomFloat(4, 0.1, 5),
            'total_saldo_dihasilkan' => fake()->randomFloat(2, 500, 10000),
            'total_poin_dihasilkan' => fake()->numberBetween(5, 100),
        ]);
    }

    /**
     * Create calculated transaction.
     */
    public function calculated(): static
    {
        return $this->state(fn (array $attributes) => [
            'calculation_performed' => true,
        ]);
    }

    /**
     * Create uncalculated transaction.
     */
    public function uncalculated(): static
    {
        return $this->state(fn (array $attributes) => [
            'calculation_performed' => false,
            'total_saldo_dihasilkan' => 0,
            'total_poin_dihasilkan' => 0,
        ]);
    }
}