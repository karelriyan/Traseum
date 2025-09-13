<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\SumberPemasukan; // Pastikan model ini ada

class SumberPemasukanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Untuk SQLite, kita menggunakan PRAGMA
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = OFF;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        }
        
        // Kosongkan tabel sebelum mengisi untuk menghindari duplikat
        SumberPemasukan::truncate();

        $sumberPemasukan = [
            ['nama_pemasukan' => 'Wisata Edukasi'],
            ['nama_pemasukan' => 'Perlombaan/Kegiatan'],
            ['nama_pemasukan' => 'Donasi'],
        ];

        // Menggunakan model untuk create agar ULID otomatis ter-generate
        foreach ($sumberPemasukan as $sumber) {
            SumberPemasukan::create($sumber);
        }

        // Aktifkan kembali pengecekan foreign key
        if (DB::getDriverName() === 'sqlite') {
            DB::statement('PRAGMA foreign_keys = ON;');
        } else {
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }
}
