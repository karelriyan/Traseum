<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🚀 Memulai seeding database Bank Sampah Digital Cipta Muri...');
        $this->command->line('');

        // Seeder dengan data statis yang sudah ada
        $this->command->info('📋 Menjalankan seeder data dasar...');
        $this->call([
            UserSeeder::class,
            SampahSeeder::class,
            RekeningSeeder::class,
            SumberPemasukanSeeder::class,
            KategoriPengeluaranSeeder::class,
        ]);

        $this->command->line('');
        $this->command->info('🏗️ Menjalankan seeder data komprehensif...');
        
        // Seeder baru dengan factory untuk data dummy yang lebih realistis
        $this->call([
            CoreBankSampahSeeder::class,    // Core: Users, Rekening, Sampah, Transaksi
            FinancialDataSeeder::class,     // Financial: Pemasukan, Pengeluaran, Transactions
            WebsiteContentSeeder::class,    // Content: News, UMKM
        ]);

        $this->command->line('');
        $this->command->info('✅ Seeding database selesai!');
        $this->command->info('🎉 Bank Sampah Digital Cipta Muri siap digunakan dengan data lengkap!');
        $this->command->line('');
        $this->command->warn('📝 Catatan: Jalankan "php artisan db:seed --class=NewsSeeder" jika ingin menambah berita khusus.');
    }
}