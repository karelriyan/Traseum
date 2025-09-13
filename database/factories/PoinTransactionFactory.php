<?php

namespace Database\Factories;

use App\Models\PoinTransaction;
use App\Models\Rekening;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PoinTransaction>
 */
class PoinTransactionFactory extends Factory
{
    protected $model = PoinTransaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['credit', 'debit'];
        $type = fake()->randomElement($types);

        // Deskripsi berdasarkan tipe transaksi poin
        $creditDescriptions = [
            'Poin dari setoran sampah plastik',
            'Poin dari setoran sampah kertas',
            'Poin dari setoran sampah logam',
            'Poin dari setoran sampah kaca',
            'Bonus poin bulanan',
            'Poin referral nasabah baru',
            'Poin kegiatan edukasi lingkungan',
            'Poin program bank sampah',
            'Poin achievement milestone',
            'Poin kompensasi',
        ];

        $debitDescriptions = [
            'Penukaran poin dengan voucher',
            'Penukaran poin dengan produk',
            'Penukaran poin dengan saldo',
            'Donasi poin untuk lingkungan',
            'Pembelian merchandise',
            'Redeem hadiah bulanan',
            'Transfer poin ke keluarga',
            'Koreksi poin',
            'Penalty keterlambatan',
            'Adjustment poin',
        ];

        $description = $type === 'credit' 
            ? fake()->randomElement($creditDescriptions)
            : fake()->randomElement($debitDescriptions);

        // Points berdasarkan tipe
        $points = $type === 'credit' 
            ? fake()->numberBetween(10, 1000)  // Credit: 10 - 1000 poin
            : fake()->numberBetween(5, 500);   // Debit: 5 - 500 poin

        return [
            'rekening_id' => Rekening::factory(),
            'type' => $type,
            'points' => $points,
            'description' => $description,
            'reference_type' => fake()->randomElement(['SetorSampah', 'Redemption', 'Transfer', 'Bonus']),
            'reference_id' => fake()->numberBetween(1, 1000),
            'user_id' => User::factory(),
            'created_at' => fake()->dateTimeBetween('-1 year', 'now'),
        ];
    }

    /**
     * Create credit transaction.
     */
    public function credit(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'credit',
            'points' => fake()->numberBetween(50, 1500),
            'description' => fake()->randomElement([
                'Poin dari setoran sampah plastik',
                'Poin dari setoran sampah kertas',
                'Bonus poin bulanan',
                'Poin kegiatan edukasi lingkungan',
            ]),
            'reference_type' => 'SetorSampah',
        ]);
    }

    /**
     * Create debit transaction.
     */
    public function debit(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'debit',
            'points' => fake()->numberBetween(10, 800),
            'description' => fake()->randomElement([
                'Penukaran poin dengan voucher',
                'Penukaran poin dengan saldo',
                'Redeem hadiah bulanan',
                'Donasi poin untuk lingkungan',
            ]),
            'reference_type' => 'Redemption',
        ]);
    }

    /**
     * Create large points transaction.
     */
    public function large(): static
    {
        return $this->state(fn (array $attributes) => [
            'points' => fake()->numberBetween(1000, 5000),
        ]);
    }

    /**
     * Create small points transaction.
     */
    public function small(): static
    {
        return $this->state(fn (array $attributes) => [
            'points' => fake()->numberBetween(1, 50),
        ]);
    }

    /**
     * Create bonus points transaction.
     */
    public function bonus(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'credit',
            'points' => fake()->numberBetween(100, 500),
            'description' => fake()->randomElement([
                'Bonus poin bulanan',
                'Poin referral nasabah baru',
                'Poin achievement milestone',
                'Poin program bank sampah',
            ]),
            'reference_type' => 'Bonus',
        ]);
    }

    /**
     * Create redemption points transaction.
     */
    public function redemption(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => 'debit',
            'points' => fake()->numberBetween(50, 1000),
            'description' => fake()->randomElement([
                'Penukaran poin dengan voucher',
                'Penukaran poin dengan produk',
                'Redeem hadiah bulanan',
                'Pembelian merchandise',
            ]),
            'reference_type' => 'Redemption',
        ]);
    }
}