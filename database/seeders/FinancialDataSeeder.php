<?php

namespace Database\Seeders;

use App\Models\Rekening;
use App\Models\User;
use App\Models\SumberPemasukan;
use App\Models\KategoriPengeluaran;
use App\Models\Pemasukan;
use App\Models\Pengeluaran;
use App\Models\PoinTransaction;
use App\Models\SaldoTransaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FinancialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->command->info('💰 Mulai seeding data keuangan...');

        // Pastikan ada data prerequisite
        $users = User::all();
        $rekenings = Rekening::all();
        
        if ($users->isEmpty() || $rekenings->isEmpty()) {
            $this->command->error('❌ Data users atau rekening tidak ditemukan. Jalankan CoreBankSampahSeeder terlebih dahulu.');
            return;
        }

        $donationAccount = Rekening::where('no_rekening', '00000000')->first();
        $coordinator = $users->where('name', 'like', '%Koordinator%')->first() ?? $users->first();

        // 1. Buat sumber pemasukan
        $this->command->info('📋 Membuat sumber pemasukan...');
        
        // Sumber pemasukan dari waste
        $sumberWaste = SumberPemasukan::factory()->waste()->createMany([
            ['nama_pemasukan' => 'Penjualan Sampah Plastik'],
            ['nama_pemasukan' => 'Penjualan Sampah Kertas'],
            ['nama_pemasukan' => 'Penjualan Sampah Logam'],
        ]);

        // Sumber pemasukan educational
        $sumberEducational = SumberPemasukan::factory()->educational()->createMany([
            ['nama_pemasukan' => 'Wisata Edukasi Bank Sampah'],
            ['nama_pemasukan' => 'Workshop Pengelolaan Sampah'],
            ['nama_pemasukan' => 'Training Lingkungan'],
        ]);

        // Sumber pemasukan partnership
        $sumberPartnership = SumberPemasukan::factory()->partnership()->createMany([
            ['nama_pemasukan' => 'CSR PT. Ramah Lingkungan'],
            ['nama_pemasukan' => 'Grant Dinas Lingkungan'],
            ['nama_pemasukan' => 'Bantuan Pemerintah Desa'],
        ]);

        $allSumberPemasukan = collect($sumberWaste)->merge($sumberEducational)->merge($sumberPartnership);

        // 2. Buat kategori pengeluaran
        $this->command->info('📋 Membuat kategori pengeluaran...');
        
        $kategoriRoutine = KategoriPengeluaran::factory()->routine()->createMany([
            ['nama_pengeluaran' => 'Operasional Harian'],
            ['nama_pengeluaran' => 'Gaji Koordinator'],
            ['nama_pengeluaran' => 'Listrik & Air'],
        ]);

        $kategoriEquipment = KategoriPengeluaran::factory()->equipment()->createMany([
            ['nama_pengeluaran' => 'Peralatan Bank Sampah'],
            ['nama_pengeluaran' => 'Perbaikan & Maintenance'],
            ['nama_pengeluaran' => 'Investasi Teknologi'],
        ]);

        $kategoriEvent = KategoriPengeluaran::factory()->event()->createMany([
            ['nama_pengeluaran' => 'Kegiatan Edukasi'],
            ['nama_pengeluaran' => 'Lomba Lingkungan'],
            ['nama_pengeluaran' => 'Workshop & Seminar'],
        ]);

        $kategoriSocial = KategoriPengeluaran::factory()->social()->createMany([
            ['nama_pengeluaran' => 'Bantuan Sosial'],
            ['nama_pengeluaran' => 'Program Kemasyarakatan'],
        ]);

        $allKategoriPengeluaran = collect($kategoriRoutine)->merge($kategoriEquipment)->merge($kategoriEvent)->merge($kategoriSocial);

        // 3. Buat pemasukan dari berbagai sumber
        $this->command->info('💵 Membuat data pemasukan...');
        
        // Pemasukan dari penjualan sampah (masuk ke akun donasi)
        for ($i = 0; $i < 25; $i++) {
            Pemasukan::factory()->fromWaste()->create([
                'sumber_pemasukan_id' => $sumberWaste->random()->id,
                'rekening_id' => $donationAccount->id,
                'user_id' => $coordinator->id,
                'tanggal_pemasukan' => fake()->dateTimeBetween('-6 months', 'now'),
            ]);
        }

        // Pemasukan tunai dari kegiatan
        for ($i = 0; $i < 15; $i++) {
            Pemasukan::factory()->cash()->create([
                'sumber_pemasukan_id' => $sumberEducational->random()->id,
                'rekening_id' => $donationAccount->id,
                'user_id' => $coordinator->id,
                'tanggal_pemasukan' => fake()->dateTimeBetween('-6 months', 'now'),
            ]);
        }

        // Pemasukan transfer dari partnership
        for ($i = 0; $i < 10; $i++) {
            Pemasukan::factory()->transfer()->create([
                'sumber_pemasukan_id' => $sumberPartnership->random()->id,
                'rekening_id' => $donationAccount->id,
                'user_id' => $coordinator->id,
                'tanggal_pemasukan' => fake()->dateTimeBetween('-6 months', 'now'),
            ]);
        }

        // 4. Buat pengeluaran dengan berbagai kategori
        $this->command->info('💸 Membuat data pengeluaran...');
        
        // Pengeluaran rutin
        for ($i = 0; $i < 20; $i++) {
            Pengeluaran::factory()->routine()->create([
                'kategori_pengeluaran_id' => $kategoriRoutine->random()->id,
                'rekening_id' => $donationAccount->id,
                'user_id' => $coordinator->id,
                'tanggal_pengeluaran' => fake()->dateTimeBetween('-6 months', 'now'),
            ]);
        }

        // Pengeluaran peralatan
        for ($i = 0; $i < 12; $i++) {
            Pengeluaran::factory()->equipment()->create([
                'kategori_pengeluaran_id' => $kategoriEquipment->random()->id,
                'rekening_id' => $donationAccount->id,
                'user_id' => $coordinator->id,
                'tanggal_pengeluaran' => fake()->dateTimeBetween('-6 months', 'now'),
            ]);
        }

        // Pengeluaran event
        for ($i = 0; $i < 15; $i++) {
            Pengeluaran::factory()->event()->create([
                'kategori_pengeluaran_id' => $kategoriEvent->random()->id,
                'rekening_id' => $donationAccount->id,
                'user_id' => $coordinator->id,
                'tanggal_pengeluaran' => fake()->dateTimeBetween('-6 months', 'now'),
            ]);
        }

        // Pengeluaran sosial
        for ($i = 0; $i < 8; $i++) {
            Pengeluaran::factory()->donation()->create([
                'kategori_pengeluaran_id' => $kategoriSocial->random()->id,
                'rekening_id' => $donationAccount->id,
                'user_id' => $coordinator->id,
                'tanggal_pengeluaran' => fake()->dateTimeBetween('-6 months', 'now'),
            ]);
        }

        // 5. Buat transaksi saldo untuk rekening nasabah
        $this->command->info('💳 Membuat transaksi saldo...');
        
        $customerRekenings = $rekenings->where('no_rekening', '!=', '00000000');
        
        foreach ($customerRekenings as $rekening) {
            // Credit transactions (dari setoran sampah - otomatis dibuat oleh sistem)
            $creditCount = fake()->numberBetween(3, 10);
            for ($i = 0; $i < $creditCount; $i++) {
                SaldoTransaction::factory()->credit()->create([
                    'rekening_id' => $rekening->id,
                    'user_id' => $rekening->user_id,
                    'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
                ]);
            }

            // Debit transactions (penarikan, transfer)
            $debitCount = fake()->numberBetween(1, 5);
            for ($i = 0; $i < $debitCount; $i++) {
                SaldoTransaction::factory()->debit()->create([
                    'rekening_id' => $rekening->id,
                    'user_id' => $rekening->user_id,
                    'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
                ]);
            }
        }

        // 6. Buat transaksi poin untuk rekening nasabah
        $this->command->info('🎯 Membuat transaksi poin...');
        
        foreach ($customerRekenings as $rekening) {
            // Credit transactions (dari setoran sampah)
            $creditCount = fake()->numberBetween(3, 10);
            for ($i = 0; $i < $creditCount; $i++) {
                PoinTransaction::factory()->credit()->create([
                    'rekening_id' => $rekening->id,
                    'user_id' => $rekening->user_id,
                    'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
                ]);
            }

            // Debit transactions (penukaran poin)
            $debitCount = fake()->numberBetween(1, 3);
            for ($i = 0; $i < $debitCount; $i++) {
                PoinTransaction::factory()->redemption()->create([
                    'rekening_id' => $rekening->id,
                    'user_id' => $rekening->user_id,
                    'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
                ]);
            }

            // Bonus transactions
            if (fake()->boolean(30)) { // 30% chance untuk bonus
                PoinTransaction::factory()->bonus()->create([
                    'rekening_id' => $rekening->id,
                    'user_id' => $rekening->user_id,
                    'created_at' => fake()->dateTimeBetween('-3 months', 'now'),
                ]);
            }
        }

        // 7. Buat transaksi besar dan kecil untuk variasi
        $this->command->info('🎲 Membuat transaksi variasi...');
        
        // Transaksi saldo besar
        SaldoTransaction::factory(5)->large()->create([
            'rekening_id' => $customerRekenings->random()->id,
            'user_id' => $users->random()->id,
        ]);

        // Transaksi saldo kecil
        SaldoTransaction::factory(10)->small()->create([
            'rekening_id' => $customerRekenings->random()->id,
            'user_id' => $users->random()->id,
        ]);

        // Transaksi poin besar
        PoinTransaction::factory(5)->large()->create([
            'rekening_id' => $customerRekenings->random()->id,
            'user_id' => $users->random()->id,
        ]);

        // Transaksi poin kecil
        PoinTransaction::factory(10)->small()->create([
            'rekening_id' => $customerRekenings->random()->id,
            'user_id' => $users->random()->id,
        ]);

        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('✅ Seeding data keuangan selesai!');
        $this->command->info("📊 Data yang dibuat:");
        $this->command->info("   - Sumber Pemasukan: " . SumberPemasukan::count());
        $this->command->info("   - Kategori Pengeluaran: " . KategoriPengeluaran::count());
        $this->command->info("   - Pemasukan: " . Pemasukan::count());
        $this->command->info("   - Pengeluaran: " . Pengeluaran::count());
        $this->command->info("   - Transaksi Saldo: " . SaldoTransaction::count());
        $this->command->info("   - Transaksi Poin: " . PoinTransaction::count());
    }
}