<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Rekening;
use App\Models\Sampah;
use App\Models\SetorSampah;
use App\Models\DetailSetorSampah;
use App\Models\SampahKeluar;
use App\Models\SampahSummary;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoreBankSampahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->command->info('🏁 Mulai seeding data core bank sampah...');

        // 1. Buat users dengan role yang beragam
        $this->command->info('👥 Membuat users...');
        $superAdmin = User::factory()->create([
            'name' => 'Super Admin Bank Sampah',
            'email' => 'admin@banksampah.com',
            'password' => bcrypt('password'),
        ]);

        $coordinator = User::factory()->create([
            'name' => 'Koordinator Bank Sampah',
            'email' => 'koordinator@banksampah.com',
            'password' => bcrypt('password'),
        ]);

        // Buat 20 users biasa
        $regularUsers = User::factory(20)->create();
        $allUsers = collect([$superAdmin, $coordinator])->merge($regularUsers);

        // 2. Buat akun donasi default
        $this->command->info('💰 Membuat akun donasi default...');
        $donationAccount = Rekening::factory()->donation()->create([
            'user_id' => null,
        ]);

        // 3. Buat rekening untuk users (tidak semua user punya rekening)
        $this->command->info('📋 Membuat rekening nasabah...');
        $rekenings = collect();
        $usersWithAccounts = $allUsers->random(15); // 15 dari 22 users punya rekening

        foreach ($usersWithAccounts as $user) {
            $rekening = Rekening::factory()->create([
                'user_id' => $user->id,
            ]);
            $rekenings->push($rekening);
        }

        // 4. Buat jenis-jenis sampah
        $this->command->info('🗑️ Membuat jenis sampah...');
        $sampahPlastik = Sampah::factory(8)->plastic()->create([
            'user_id' => $coordinator->id,
        ]);
        
        $sampahKertas = Sampah::factory(6)->paper()->create([
            'user_id' => $coordinator->id,
        ]);
        
        $sampahLogam = Sampah::factory(4)->metal()->create([
            'user_id' => $coordinator->id,
        ]);

        $allSampah = $sampahPlastik->merge($sampahKertas)->merge($sampahLogam);

        // 5. Buat transaksi setor sampah
        $this->command->info('📦 Membuat transaksi setor sampah...');
        
        // Setor sampah ke rekening biasa (80% dari total)
        $setorSampahRekening = collect();
        for ($i = 0; $i < 40; $i++) {
            $rekening = $rekenings->random();
            $setorSampah = SetorSampah::factory()->account()->create([
                'rekening_id' => $rekening->id,
                'user_id' => $allUsers->random()->id,
                'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
            ]);
            $setorSampahRekening->push($setorSampah);
        }

        // Setor sampah donasi (20% dari total)
        $setorSampahDonasi = collect();
        for ($i = 0; $i < 10; $i++) {
            $setorSampah = SetorSampah::factory()->donation()->create([
                'rekening_id' => $donationAccount->id,
                'user_id' => $allUsers->random()->id,
                'created_at' => fake()->dateTimeBetween('-6 months', 'now'),
            ]);
            $setorSampahDonasi->push($setorSampah);
        }

        $allSetorSampah = $setorSampahRekening->merge($setorSampahDonasi);

        // 6. Buat detail setor sampah untuk setiap transaksi
        $this->command->info('📋 Membuat detail setor sampah...');
        foreach ($allSetorSampah as $setorSampah) {
            $jumlahDetail = fake()->numberBetween(1, 5); // 1-5 jenis sampah per transaksi
            $selectedSampah = $allSampah->random($jumlahDetail);
            
            $totalBerat = 0;
            $totalSaldo = 0;
            $totalPoin = 0;

            foreach ($selectedSampah as $sampah) {
                $detail = DetailSetorSampah::factory()->create([
                    'setor_sampah_id' => $setorSampah->id,
                    'sampah_id' => $sampah->id,
                    'user_id' => $setorSampah->user_id,
                ]);

                // Hitung total
                $totalBerat += $detail->berat;
                $totalSaldo += $sampah->saldo_per_kg * $detail->berat;
                $totalPoin += $sampah->poin_per_kg * $detail->berat;
            }

            // Update total di setor sampah
            $setorSampah->update([
                'berat' => $totalBerat,
                'total_saldo_dihasilkan' => $totalSaldo,
                'total_poin_dihasilkan' => $totalPoin,
                'calculation_performed' => true,
            ]);
        }

        // 7. Buat sampah keluar
        $this->command->info('🚛 Membuat data sampah keluar...');
        
        // Sampah keluar plastik
        SampahKeluar::factory(15)->plastic()->create([
            'sampah_id' => $sampahPlastik->random()->id,
            'user_id' => $coordinator->id,
        ]);

        // Sampah keluar kertas
        SampahKeluar::factory(12)->paper()->create([
            'sampah_id' => $sampahKertas->random()->id,
            'user_id' => $coordinator->id,
        ]);

        // Sampah keluar logam
        SampahKeluar::factory(8)->metal()->create([
            'sampah_id' => $sampahLogam->random()->id,
            'user_id' => $coordinator->id,
        ]);

        // 8. Buat sampah summary
        $this->command->info('📊 Membuat sampah summary...');
        
        foreach ($allSampah as $sampah) {
            // Summary bulanan untuk 6 bulan terakhir
            for ($month = 5; $month >= 0; $month--) {
                SampahSummary::factory()->create([
                    'sampah_id' => $sampah->id,
                    'tanggal_summary' => now()->subMonths($month)->startOfMonth(),
                    'user_id' => $coordinator->id,
                ]);
            }
        }

        // Buat beberapa summary dengan kondisi khusus
        SampahSummary::factory(10)->highTurnover()->create([
            'sampah_id' => $allSampah->random()->id,
            'user_id' => $coordinator->id,
        ]);

        SampahSummary::factory(5)->lowTurnover()->create([
            'sampah_id' => $allSampah->random()->id,
            'user_id' => $coordinator->id,
        ]);

        SampahSummary::factory(3)->noOutflow()->create([
            'sampah_id' => $allSampah->random()->id,
            'user_id' => $coordinator->id,
        ]);

        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('✅ Seeding data core bank sampah selesai!');
        $this->command->info("📊 Data yang dibuat:");
        $this->command->info("   - Users: " . User::count());
        $this->command->info("   - Rekening: " . Rekening::count());
        $this->command->info("   - Jenis Sampah: " . Sampah::count());
        $this->command->info("   - Setor Sampah: " . SetorSampah::count());
        $this->command->info("   - Detail Setor: " . DetailSetorSampah::count());
        $this->command->info("   - Sampah Keluar: " . SampahKeluar::count());
        $this->command->info("   - Sampah Summary: " . SampahSummary::count());
    }
}