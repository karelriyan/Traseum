<?php

namespace Database\Factories;

use App\Models\PostinganUmkm;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PostinganUmkm>
 */
class PostinganUmkmFactory extends Factory
{
    protected $model = PostinganUmkm::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kategoriUmkm = [
            'Makanan & Minuman',
            'Kerajinan Tangan',
            'Fashion & Aksesoris',
            'Produk Daur Ulang',
            'Tanaman & Pertanian',
            'Jasa & Layanan',
            'Elektronik & Gadget',
            'Kecantikan & Kesehatan',
            'Pendidikan & Kursus',
            'Otomotif',
        ];

        $namaUmkm = [
            'Warung Mak Yuni',
            'Kerajinan Bambu Lestari',
            'Tas Rajut Ibu Sari',
            'EcoMade Products',
            'Kebun Organik Hijau',
            'Laundry Express',
            'Gadget Corner',
            'Herbal Sehat Alami',
            'Les Privat Cerdas',
            'Bengkel Motor Jaya',
            'Bakso Pak Harto',
            'Anyaman Pandan Indah',
            'Hijab Syari Collection',
            'Recycle Art Studio',
            'Hidroponik Modern',
            'Catering Berkah',
            'Aksesoris Vintage',
            'Jamu Tradisional',
            'Kursus Komputer',
            'Service HP Murah',
        ];

        $selectedNama = fake()->randomElement($namaUmkm);
        $selectedKategori = fake()->randomElement($kategoriUmkm);

        // Generate product/service description based on category
        $deskripsi = $this->generateDescriptionByCategory($selectedKategori, $selectedNama);

        // Generate contact info
        $nomorWa = '08' . fake()->numerify('##########');
        $alamat = fake()->streetAddress() . ', ' . fake()->city();

        // Generate price range based on category
        $hargaRange = $this->generatePriceByCategory($selectedKategori);

        return [
            'nama_umkm' => $selectedNama,
            'kategori' => $selectedKategori,
            'deskripsi' => $deskripsi,
            'nomor_wa' => $nomorWa,
            'alamat' => $alamat,
            'harga' => $hargaRange,
            'gambar_url' => fake()->imageUrl(400, 300, 'business', true, $selectedKategori),
            'status' => fake()->randomElement(['aktif', 'non-aktif']),
            'rating' => fake()->randomFloat(1, 3.0, 5.0),
            'jumlah_ulasan' => fake()->numberBetween(0, 100),
            'user_id' => User::factory(),
        ];
    }

    /**
     * Generate description based on category.
     */
    private function generateDescriptionByCategory(string $kategori, string $nama): string
    {
        return match($kategori) {
            'Makanan & Minuman' => "UMKM $nama menyediakan berbagai pilihan makanan dan minuman segar dengan cita rasa khas rumahan. Menggunakan bahan-bahan berkualitas dan resep turun temurun yang telah terpercaya. Melayani pesanan untuk acara keluarga, kantor, dan berbagai event.",
            
            'Kerajinan Tangan' => "Kreativitas tanpa batas dari $nama menghadirkan berbagai kerajinan tangan unik dan berkualitas. Setiap produk dibuat dengan detail dan penuh cinta, cocok untuk hadiah, dekorasi rumah, atau koleksi pribadi. Menerima pesanan custom sesuai keinginan.",
            
            'Fashion & Aksesoris' => "$nama hadir dengan koleksi fashion dan aksesoris terkini yang mengikuti tren masa kini. Produk berkualitas dengan harga terjangkau, cocok untuk berbagai kalangan dan acara. Tersedia berbagai ukuran dan warna pilihan.",
            
            'Produk Daur Ulang' => "Mendukung gerakan lingkungan hijau, $nama mengolah sampah menjadi produk bernilai ekonomis. Setiap produk ramah lingkungan dan memiliki fungsi praktis untuk kehidupan sehari-hari. Mari bersama menjaga kelestarian bumi!",
            
            'Tanaman & Pertanian' => "$nama menyediakan berbagai jenis tanaman hias, sayuran organik, dan produk pertanian segar. Tanaman sehat dan subur, cocok untuk taman rumah atau bisnis. Tersedia juga pupuk organik dan perlengkapan berkebun.",
            
            'Jasa & Layanan' => "Layanan profesional dari $nama siap membantu kebutuhan Anda dengan kualitas terbaik dan harga kompetitif. Tim berpengalaman dan terpercaya, mengutamakan kepuasan pelanggan. Konsultasi gratis untuk layanan terbaik.",
            
            'Elektronik & Gadget' => "$nama menjual berbagai produk elektronik dan gadget terbaru dengan garansi resmi. Harga bersaing dengan kualitas terjamin. Melayani service dan reparasi untuk berbagai merk elektronik.",
            
            'Kecantikan & Kesehatan' => "Produk kecantikan dan kesehatan alami dari $nama dibuat dari bahan-bahan pilihan yang aman dan teruji. Membantu merawat kecantikan dan menjaga kesehatan secara natural tanpa efek samping berbahaya.",
            
            'Pendidikan & Kursus' => "$nama menyediakan layanan pendidikan dan kursus berkualitas dengan instruktur berpengalaman. Metode pembelajaran yang menyenangkan dan efektif. Tersedia kelas reguler dan privat sesuai kebutuhan.",
            
            'Otomotif' => "Layanan otomotif terpercaya dari $nama dengan teknisi berpengalaman dan peralatan modern. Melayani service rutin, reparasi, dan modifikasi kendaraan. Spare part original dan KW dengan garansi.",
            
            default => "UMKM $nama berkomitmen memberikan produk dan layanan terbaik untuk kepuasan pelanggan. Kualitas terjamin dengan harga yang bersahabat."
        };
    }

    /**
     * Generate price range based on category.
     */
    private function generatePriceByCategory(string $kategori): string
    {
        return match($kategori) {
            'Makanan & Minuman' => 'Rp ' . fake()->numberBetween(5, 50) . '.000 - Rp ' . fake()->numberBetween(50, 200) . '.000',
            'Kerajinan Tangan' => 'Rp ' . fake()->numberBetween(10, 100) . '.000 - Rp ' . fake()->numberBetween(100, 500) . '.000',
            'Fashion & Aksesoris' => 'Rp ' . fake()->numberBetween(25, 150) . '.000 - Rp ' . fake()->numberBetween(150, 800) . '.000',
            'Produk Daur Ulang' => 'Rp ' . fake()->numberBetween(15, 75) . '.000 - Rp ' . fake()->numberBetween(75, 300) . '.000',
            'Tanaman & Pertanian' => 'Rp ' . fake()->numberBetween(5, 25) . '.000 - Rp ' . fake()->numberBetween(25, 150) . '.000',
            'Jasa & Layanan' => 'Rp ' . fake()->numberBetween(20, 100) . '.000 - Rp ' . fake()->numberBetween(100, 1000) . '.000',
            'Elektronik & Gadget' => 'Rp ' . fake()->numberBetween(50, 500) . '.000 - Rp ' . fake()->numberBetween(500, 5000) . '.000',
            'Kecantikan & Kesehatan' => 'Rp ' . fake()->numberBetween(15, 80) . '.000 - Rp ' . fake()->numberBetween(80, 400) . '.000',
            'Pendidikan & Kursus' => 'Rp ' . fake()->numberBetween(50, 200) . '.000 - Rp ' . fake()->numberBetween(200, 1000) . '.000',
            'Otomotif' => 'Rp ' . fake()->numberBetween(30, 200) . '.000 - Rp ' . fake()->numberBetween(200, 2000) . '.000',
            default => 'Rp ' . fake()->numberBetween(10, 100) . '.000 - Rp ' . fake()->numberBetween(100, 500) . '.000'
        };
    }

    /**
     * Create active UMKM.
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'aktif',
            'rating' => fake()->randomFloat(1, 4.0, 5.0),
            'jumlah_ulasan' => fake()->numberBetween(10, 100),
        ]);
    }

    /**
     * Create inactive UMKM.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'non-aktif',
            'rating' => fake()->randomFloat(1, 2.0, 4.0),
            'jumlah_ulasan' => fake()->numberBetween(0, 20),
        ]);
    }

    /**
     * Create popular UMKM.
     */
    public function popular(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'aktif',
            'rating' => fake()->randomFloat(1, 4.5, 5.0),
            'jumlah_ulasan' => fake()->numberBetween(50, 200),
        ]);
    }

    /**
     * Create food category UMKM.
     */
    public function food(): static
    {
        return $this->state(fn (array $attributes) => [
            'kategori' => 'Makanan & Minuman',
            'nama_umkm' => fake()->randomElement([
                'Warung Mak Yuni', 'Bakso Pak Harto', 'Catering Berkah',
                'Kedai Kopi Pagi', 'Sate Ayam Delima', 'Es Campur Segar'
            ]),
        ]);
    }

    /**
     * Create handicraft category UMKM.
     */
    public function handicraft(): static
    {
        return $this->state(fn (array $attributes) => [
            'kategori' => 'Kerajinan Tangan',
            'nama_umkm' => fake()->randomElement([
                'Kerajinan Bambu Lestari', 'Anyaman Pandan Indah', 'Recycle Art Studio',
                'Ukiran Kayu Jati', 'Tenun Tradisional', 'Keramik Artistik'
            ]),
        ]);
    }

    /**
     * Create eco-friendly category UMKM.
     */
    public function ecoFriendly(): static
    {
        return $this->state(fn (array $attributes) => [
            'kategori' => 'Produk Daur Ulang',
            'nama_umkm' => fake()->randomElement([
                'EcoMade Products', 'Recycle Art Studio', 'Green Craft',
                'Eco Bag Creative', 'Plastik Jadi Cantik', 'Daur Ulang Kreatif'
            ]),
        ]);
    }

    /**
     * Create high-rated UMKM.
     */
    public function highRated(): static
    {
        return $this->state(fn (array $attributes) => [
            'rating' => fake()->randomFloat(1, 4.7, 5.0),
            'jumlah_ulasan' => fake()->numberBetween(30, 150),
            'status' => 'aktif',
        ]);
    }
}