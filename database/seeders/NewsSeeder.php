<?php

namespace Database\Seeders;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class NewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        $newsData = [
            [
                'title' => 'Program Baru Bank Sampah Digital Cipta Muri',
                'content' => '<p>Kami dengan bangga mengumumkan program terbaru Bank Sampah Digital Cipta Muri yang akan membantu masyarakat dalam mengelola sampah dengan lebih efektif dan ramah lingkungan.</p><p>Program ini mencakup sistem digital yang memungkinkan nasabah untuk melacak tabungan sampah mereka secara real-time, mendapatkan poin reward, dan menukarkannya dengan berbagai hadiah menarik.</p>',
                'category' => 'program',
                'status' => 'published',
                'published_at' => now()->subDays(2),
                'tags' => ['bank sampah', 'digital', 'lingkungan'],
            ],
            [
                'title' => 'Pelatihan Pengelolaan Sampah Organik',
                'content' => '<p>Bergabunglah dengan kami dalam pelatihan pengelolaan sampah organik yang akan dilaksanakan pada akhir bulan ini. Pelatihan ini ditujukan untuk meningkatkan kesadaran masyarakat tentang pentingnya pengelolaan sampah yang baik.</p><p>Peserta akan belajar cara mengolah sampah organik menjadi kompos berkualitas tinggi.</p>',
                'category' => 'event',
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'tags' => ['pelatihan', 'organik', 'kompos'],
            ],
            [
                'title' => 'Kemitraan dengan Sekolah-Sekolah Setempat',
                'content' => '<p>Bank Sampah Digital Cipta Muri menjalin kemitraan strategis dengan sekolah-sekolah di wilayah setempat untuk meningkatkan kesadaran lingkungan sejak dini.</p><p>Program ini akan mencakup edukasi langsung ke sekolah, kompetisi daur ulang, dan program tabungan sampah khusus siswa.</p>',
                'category' => 'announcement',
                'status' => 'published',
                'published_at' => now()->subWeek(),
                'tags' => ['kemitraan', 'sekolah', 'edukasi'],
            ],
            [
                'title' => 'Tips Mengurangi Sampah Plastik di Rumah',
                'content' => '<p>Dalam artikel ini, kami akan berbagi tips praktis untuk mengurangi penggunaan plastik di rumah tangga:</p><ul><li>Gunakan tas belanja yang dapat digunakan berulang</li><li>Pilih produk dengan kemasan minimal</li><li>Manfaatkan wadah makanan yang dapat digunakan kembali</li><li>Daur ulang botol plastik menjadi pot tanaman</li></ul>',
                'category' => 'education',
                'status' => 'published',
                'published_at' => now()->subDays(3),
                'tags' => ['tips', 'plastik', 'rumah tangga'],
            ],
            [
                'title' => 'Inovasi Teknologi Pengolahan Sampah Terbaru',
                'content' => '<p>Bank Sampah Digital Cipta Muri terus berinovasi dengan menghadirkan teknologi terbaru dalam pengolahan sampah. Teknologi ini mampu mengolah berbagai jenis sampah dengan lebih efisien.</p><p>Sistem otomatis yang kami kembangkan dapat memilah sampah berdasarkan jenisnya secara otomatis, meningkatkan produktivitas hingga 300%.</p>',
                'category' => 'achievement',
                'status' => 'draft',
                'published_at' => null,
                'tags' => ['teknologi', 'inovasi', 'otomatis'],
            ],
        ];

        foreach ($newsData as $data) {
            News::create([
                'title' => $data['title'],
                'slug' => Str::slug($data['title']),
                'content' => $data['content'],
                'category' => $data['category'],
                'status' => $data['status'],
                'published_at' => $data['published_at'],
                'author_id' => $user ? $user->id : null,
                'tags' => $data['tags'],
                'views_count' => rand(10, 500),
            ]);
        }
    }
}
