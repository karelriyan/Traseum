<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\PostinganUmkm;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WebsiteContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nonaktifkan foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        $this->command->info('🌐 Mulai seeding konten website...');

        // Pastikan ada users
        $users = User::all();
        if ($users->isEmpty()) {
            $this->command->error('❌ Data users tidak ditemukan. Jalankan CoreBankSampahSeeder terlebih dahulu.');
            return;
        }

        $author = $users->where('name', 'like', '%Koordinator%')->first() ?? $users->first();
        $adminUser = $users->where('name', 'like', '%Admin%')->first() ?? $users->first();

        // 1. Buat berita dengan berbagai kategori
        $this->command->info('📰 Membuat artikel berita...');

        // Berita program
        $programNews = News::factory(8)->program()->published()->create([
            'author_id' => $author->id,
        ]);

        // Berita event
        $eventNews = News::factory(6)->event()->published()->create([
            'author_id' => $author->id,
        ]);

        // Berita achievement
        $achievementNews = News::factory(4)->achievement()->published()->create([
            'author_id' => $author->id,
        ]);

        // Berita draft (belum publish)
        $draftNews = News::factory(3)->draft()->create([
            'author_id' => $author->id,
        ]);

        // Berita populer
        $popularNews = News::factory(5)->popular()->create([
            'author_id' => $author->id,
        ]);

        // Berita terbaru
        $recentNews = News::factory(4)->recent()->create([
            'author_id' => $author->id,
        ]);

        // 2. Buat postingan UMKM dengan berbagai kategori
        $this->command->info('🏪 Membuat postingan UMKM...');

        // UMKM Makanan & Minuman
        $foodUmkm = PostinganUmkm::factory(8)->food()->active()->create([
            'user_id' => $users->random()->id,
        ]);

        // UMKM Kerajinan Tangan
        $handicraftUmkm = PostinganUmkm::factory(6)->handicraft()->active()->create([
            'user_id' => $users->random()->id,
        ]);

        // UMKM Produk Daur Ulang (sesuai tema bank sampah)
        $ecoUmkm = PostinganUmkm::factory(5)->ecoFriendly()->active()->create([
            'user_id' => $users->random()->id,
        ]);

        // UMKM dengan rating tinggi
        $highRatedUmkm = PostinganUmkm::factory(4)->highRated()->create([
            'user_id' => $users->random()->id,
        ]);

        // UMKM populer
        $popularUmkm = PostinganUmkm::factory(5)->popular()->create([
            'user_id' => $users->random()->id,
        ]);

        // UMKM non-aktif (untuk variasi data)
        $inactiveUmkm = PostinganUmkm::factory(3)->inactive()->create([
            'user_id' => $users->random()->id,
        ]);

        // UMKM dengan berbagai kategori lainnya
        $otherCategories = [
            'Fashion & Aksesoris',
            'Tanaman & Pertanian', 
            'Jasa & Layanan',
            'Elektronik & Gadget',
            'Kecantikan & Kesehatan',
            'Pendidikan & Kursus',
            'Otomotif'
        ];

        foreach ($otherCategories as $kategori) {
            PostinganUmkm::factory(2)->create([
                'kategori' => $kategori,
                'user_id' => $users->random()->id,
                'status' => fake()->randomElement(['aktif', 'aktif', 'aktif', 'non-aktif']), // 75% aktif
            ]);
        }

        // 3. Buat konten khusus dengan tema bank sampah
        $this->command->info('♻️ Membuat konten khusus bank sampah...');

        // Berita khusus bank sampah
        $bankSampahNews = [
            [
                'title' => 'Inovasi Terbaru Bank Sampah Digital Cipta Muri',
                'category' => 'program',
                'content' => '<p>Bank Sampah Digital Cipta Muri memperkenalkan sistem terbaru yang memungkinkan nasabah untuk memantau saldo dan poin mereka secara real-time melalui platform digital.</p><p>Sistem ini dilengkapi dengan fitur notifikasi otomatis, riwayat transaksi lengkap, dan program reward yang menarik untuk mendorong partisipasi masyarakat dalam pengelolaan sampah berkelanjutan.</p><p>Dengan teknologi QR Code dan aplikasi mobile, proses setor sampah menjadi lebih mudah dan transparan.</p>',
                'tags' => ['inovasi', 'digital', 'bank sampah', 'teknologi', 'lingkungan'],
            ],
            [
                'title' => 'Program Edukasi Sekolah Tentang Pengelolaan Sampah',
                'category' => 'education',
                'content' => '<p>Bank Sampah Cipta Muri bekerja sama dengan 15 sekolah di wilayah sekitar untuk menyelenggarakan program edukasi pengelolaan sampah sejak dini.</p><p>Program ini mencakup workshop praktis, kompetisi kreativitas daur ulang, dan pembentukan bank sampah mini di setiap sekolah peserta.</p><p>Diharapkan program ini dapat membentuk generasi yang peduli lingkungan dan memahami pentingnya ekonomi sirkular.</p>',
                'tags' => ['edukasi', 'sekolah', 'anak-anak', 'program', 'lingkungan'],
            ],
            [
                'title' => 'Pencapaian 50 Ton Sampah Daur Ulang di Tahun 2024',
                'category' => 'achievement',
                'content' => '<p>Bank Sampah Digital Cipta Muri dengan bangga mengumumkan pencapaian luar biasa di tahun 2024 dengan berhasil mengumpulkan dan mengolah 50 ton sampah untuk didaur ulang.</p><p>Pencapaian ini merupakan hasil kolaborasi yang solid antara tim bank sampah, masyarakat, dan mitra industri daur ulang.</p><p>Dari 50 ton tersebut, 35 ton adalah sampah plastik, 12 ton kertas, dan 3 ton logam, yang semuanya telah disalurkan ke fasilitas daur ulang yang tepat.</p>',
                'tags' => ['achievement', '50 ton', 'daur ulang', 'plastik', 'kertas', 'logam'],
            ]
        ];

        foreach ($bankSampahNews as $newsData) {
            News::factory()->create(array_merge($newsData, [
                'author_id' => $author->id,
                'status' => 'published',
                'published_at' => fake()->dateTimeBetween('-2 months', 'now'),
                'views_count' => fake()->numberBetween(100, 1000),
            ]));
        }

        // UMKM khusus produk daur ulang
        $ecoFriendlyUmkm = [
            [
                'nama_umkm' => 'EcoArt Creative Studio',
                'kategori' => 'Produk Daur Ulang',
                'deskripsi' => 'Mengolah sampah plastik menjadi berbagai produk seni dan kerajinan yang indah dan fungsional. Setiap produk dibuat dengan teknik khusus yang ramah lingkungan, memberikan nilai estetika tinggi sambil mendukung program pengurangan sampah plastik di lingkungan.',
                'harga' => 'Rp 25.000 - Rp 250.000',
                'rating' => 4.8,
                'jumlah_ulasan' => 67,
            ],
            [
                'nama_umkm' => 'GreenBag Revolution',
                'kategori' => 'Produk Daur Ulang',
                'deskripsi' => 'Spesialis pembuatan tas dan dompet dari bahan daur ulang seperti plastik bekas, banner, dan kain perca. Produk berkualitas tinggi dengan desain modern dan tahan lama. Mendukung gerakan zero waste lifestyle dengan produk yang stylish dan eco-friendly.',
                'harga' => 'Rp 35.000 - Rp 150.000',
                'rating' => 4.6,
                'jumlah_ulasan' => 89,
            ],
            [
                'nama_umkm' => 'Recycle Home Decor',
                'kategori' => 'Produk Daur Ulang',
                'deskripsi' => 'Menciptakan dekorasi rumah unik dari sampah organik dan anorganik yang telah diolah. Pot tanaman dari botol bekas, lampu hias dari kardus, dan berbagai hiasan rumah kreatif lainnya. Semua produk aman, berkualitas, dan memberikan sentuhan artistik pada rumah Anda.',
                'harga' => 'Rp 15.000 - Rp 300.000',
                'rating' => 4.5,
                'jumlah_ulasan' => 45,
            ]
        ];

        foreach ($ecoFriendlyUmkm as $umkmData) {
            PostinganUmkm::factory()->create(array_merge($umkmData, [
                'user_id' => $users->random()->id,
                'status' => 'aktif',
                'alamat' => fake()->streetAddress() . ', ' . fake()->city(),
                'nomor_wa' => '08' . fake()->numerify('##########'),
                'gambar_url' => fake()->imageUrl(400, 300, 'recycling', true, 'eco-friendly'),
            ]));
        }

        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->command->info('✅ Seeding konten website selesai!');
        $this->command->info("📊 Data yang dibuat:");
        $this->command->info("   - Total Berita: " . News::count());
        $this->command->info("     • Published: " . News::where('status', 'published')->count());
        $this->command->info("     • Draft: " . News::where('status', 'draft')->count());
        $this->command->info("   - Total UMKM: " . PostinganUmkm::count());
        $this->command->info("     • Aktif: " . PostinganUmkm::where('status', 'aktif')->count());
        $this->command->info("     • Non-aktif: " . PostinganUmkm::where('status', 'non-aktif')->count());
        $this->command->info("     • Produk Daur Ulang: " . PostinganUmkm::where('kategori', 'Produk Daur Ulang')->count());
    }
}