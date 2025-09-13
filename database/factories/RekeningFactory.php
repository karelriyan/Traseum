<?php

namespace Database\Factories;

use App\Models\Rekening;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rekening>
 */
class RekeningFactory extends Factory
{
    protected $model = Rekening::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generate unique no_rekening dalam format 8 digit
        $noRekening = str_pad(fake()->unique()->numberBetween(10000000, 99999999), 8, '0', STR_PAD_LEFT);
        
        // Generate unique NIK dalam format 16 digit
        $nik = str_pad(fake()->unique()->numberBetween(1000000000000000, 9999999999999999), 16, '0', STR_PAD_LEFT);
        
        // Generate unique no_kk dalam format 16 digit (berbeda dari NIK)
        do {
            $noKk = str_pad(fake()->unique()->numberBetween(1000000000000000, 9999999999999999), 16, '0', STR_PAD_LEFT);
        } while ($noKk === $nik);

        $genders = ['Laki-laki', 'Perempuan'];
        $pendidikan = ['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'S3', 'Lainnya'];
        $pekerjaan = [
            'Petani', 'Wiraswasta', 'PNS', 'Karyawan Swasta', 'Buruh', 
            'Pedagang', 'Guru', 'Pensiunan', 'Ibu Rumah Tangga', 'Mahasiswa'
        ];

        // Nama dusun yang realistis untuk Indonesia
        $dusun = [
            'Krajan', 'Sumber', 'Kauman', 'Ngabean', 'Ketapang', 'Rejoso',
            'Wringin', 'Tanggul', 'Kebalen', 'Patihan', 'Kaliwungu'
        ];

        return [
            'no_rekening' => $noRekening,
            'nama' => fake()->name(),
            'dusun' => fake()->randomElement($dusun),
            'rt' => str_pad(fake()->numberBetween(1, 20), 2, '0', STR_PAD_LEFT),
            'rw' => str_pad(fake()->numberBetween(1, 10), 2, '0', STR_PAD_LEFT),
            'gender' => fake()->randomElement($genders),
            'no_kk' => $noKk,
            'nik' => $nik,
            'tanggal_lahir' => fake()->dateTimeBetween('-70 years', '-17 years'),
            'pendidikan' => fake()->randomElement($pendidikan),
            'pekerjaan' => fake()->randomElement($pekerjaan),
            'alamat' => fake()->streetAddress(),
            'no_telepon' => fake()->phoneNumber(),
            'balance' => fake()->randomFloat(2, 0, 500000), // Saldo 0 - 500k
            'points_balance' => fake()->numberBetween(0, 10000), // Poin 0 - 10k
            'user_id' => User::factory(),
        ];
    }

    /**
     * Indicate that this is the default donation account.
     */
    public function donation(): static
    {
        return $this->state(fn (array $attributes) => [
            'no_rekening' => '00000000',
            'nama' => 'Tabungan Bank Sampah',
            'dusun' => '-',
            'rt' => '-',
            'rw' => '-',
            'gender' => '-',
            'no_kk' => '0000000000000000',
            'nik' => '0000000000000000',
            'pendidikan' => '-',
            'pekerjaan' => '-',
            'alamat' => '-',
            'no_telepon' => '-',
            'balance' => 0,
            'points_balance' => 0,
            'user_id' => null,
        ]);
    }
}