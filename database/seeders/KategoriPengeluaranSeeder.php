<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\KategoriPengeluaran; // Pastikan model ini ada

class KategoriPengeluaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan pengecekan foreign key untuk sementara jika perlu
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Kosongkan tabel sebelum mengisi
        KategoriPengeluaran::truncate();

        $kategoriPengeluaran = [
            ['nama_pengeluaran' => 'Rutin'],
            ['nama_pengeluaran' => 'Kebutuhan'],
            ['nama_pengeluaran' => 'Perlombaan/Kegiatan'],
            ['nama_pengeluaran' => 'Donasi'],
        ];

        // Menggunakan model untuk create agar ULID otomatis ter-generate
        foreach ($kategoriPengeluaran as $kategori) {
            KategoriPengeluaran::create($kategori);
        }

        // Aktifkan kembali pengecekan foreign key
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
