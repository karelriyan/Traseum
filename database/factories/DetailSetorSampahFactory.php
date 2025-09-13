<?php

namespace Database\Factories;

use App\Models\DetailSetorSampah;
use App\Models\SetorSampah;
use App\Models\Sampah;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DetailSetorSampah>
 */
class DetailSetorSampahFactory extends Factory
{
    protected $model = DetailSetorSampah::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'setor_sampah_id' => SetorSampah::factory(),
            'sampah_id' => Sampah::factory(),
            'berat' => fake()->randomFloat(4, 0.1, 20), // 0.1kg - 20kg per item
            'user_id' => User::factory(),
        ];
    }

    /**
     * Create detail for plastic waste.
     */
    public function plastic(): static
    {
        return $this->state(fn (array $attributes) => [
            'sampah_id' => Sampah::factory()->plastic(),
            'berat' => fake()->randomFloat(4, 0.5, 10), // Plastik biasanya ringan
        ]);
    }

    /**
     * Create detail for paper waste.
     */
    public function paper(): static
    {
        return $this->state(fn (array $attributes) => [
            'sampah_id' => Sampah::factory()->paper(),
            'berat' => fake()->randomFloat(4, 1, 15), // Kertas berat sedang
        ]);
    }

    /**
     * Create detail for metal waste.
     */
    public function metal(): static
    {
        return $this->state(fn (array $attributes) => [
            'sampah_id' => Sampah::factory()->metal(),
            'berat' => fake()->randomFloat(4, 0.1, 5), // Logam berat tapi volume kecil
        ]);
    }

    /**
     * Create light weight detail.
     */
    public function light(): static
    {
        return $this->state(fn (array $attributes) => [
            'berat' => fake()->randomFloat(4, 0.1, 2),
        ]);
    }

    /**
     * Create heavy weight detail.
     */
    public function heavy(): static
    {
        return $this->state(fn (array $attributes) => [
            'berat' => fake()->randomFloat(4, 5, 20),
        ]);
    }
}