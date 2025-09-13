<?php

namespace Database\Factories;

use App\Models\SaldoTransaction;
use App\Models\Rekening;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SaldoTransaction>
 */
class SaldoTransactionFactory extends Factory
{
    protected $model = SaldoTransaction::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = ['credit', 'debit'];
        $type = fake()->randomElement($types);

        // Deskripsi berdasarkan tipe transaksi
        $creditDescriptions = [
            'Penjualan sampah plastik',
            'Penjualan sampah kertas',
            'Penjualan sampah logam',
            'Penjualan sampah kaca',
            'Setoran sampah campuran',
            'Bonus poin konversi',
            'Reward program lingkungan',
            'Insentif edukasi',
        ];

        $debitDescriptions = [
            'Penarikan tunai',
            'Pembelian produk daur ulang',
            'Biaya administrasi',
            'Transfer ke rekening lain',
            'Pembelian voucher',
            'Donasi lingkungan',
            'Pembayaran iuran',
            'Koreksi saldo',
        ];

        $description = $type === 'credit' 
            ? fake()->randomElement($creditDescriptions)
            : fake()->randomElement($debitDescriptions);

        // Amount berdasarkan tipe
        $amount = $type === 'credit' 
            ? fake()->randomFloat(2, 1000, 100000) // Credit: 1k - 100k
            : fake()->randomFloat(2, 500, 50000);  // Debit: 500 - 50k

        return [
            'rekening_id' => Rekening::factory(),
            'type' => $type,
            'amount' => $amount,
            'description' => $description,
            'reference_type' => fake()->randomElement(['SetorSampah', 'Withdrawal', 'Transfer', 'Adjustment']),
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
            'amount' => fake()->randomFloat(2, 5000, 150000),
            'description' => fake()->randomElement([
                'Penjualan sampah plastik',
                'Penjualan sampah kertas',
                'Penjualan sampah logam',
                'Setoran sampah campuran',
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
            'amount' => fake()->randomFloat(2, 1000, 75000),
            'description' => fake()->randomElement([
                'Penarikan tunai',
                'Transfer ke rekening lain',
                'Pembelian voucher',
                'Donasi lingkungan',
            ]),
            'reference_type' => 'Withdrawal',
        ]);
    }

    /**
     * Create large amount transaction.
     */
    public function large(): static
    {
        return $this->state(fn (array $attributes) => [
            'amount' => fake()->randomFloat(2, 100000, 500000),
        ]);
    }

    /**
     * Create small amount transaction.
     */
    public function small(): static
    {
        return $this->state(fn (array $attributes) => [
            'amount' => fake()->randomFloat(2, 100, 5000),
        ]);
    }

    /**
     * Create recent transaction.
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ]);
    }
}