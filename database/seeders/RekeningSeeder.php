<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rekening;
use Illuminate\Support\Carbon;

class RekeningSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Menggunakan updateOrCreate untuk mencegah duplikasi jika seeder dijalankan lagi
        // Kunci pencarian adalah 'no_rekening'
        Rekening::updateOrCreate(
            ['no_rekening' => '00000000'],
            [
                'nama'          => 'Tabungan Bank Sampah',
                'dusun'         => '-',
                'rt'            => '-',
                'rw'            => '-',
                'gender'        => '-',
                'no_kk'         => '0000000000000000', // Harus unik
                'nik'           => '0000000000000000', // Harus unik dan berbeda dari no_kk
                'tanggal_lahir' => Carbon::now(),
                'pendidikan'    => '-',
                'balance'       => 0, // Saldo awal
                'points_balance'=> 0, // Poin awal
            ]
        );
    }
}
