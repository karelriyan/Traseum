<?php

namespace Database\Factories;

use App\Models\News;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\News>
 */
class NewsFactory extends Factory
{
    protected $model = News::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = ['program', 'event', 'announcement', 'achievement', 'education', 'partnership'];
        $statuses = ['published', 'draft'];
        
        $titles = [
            'Program Baru Bank Sampah Digital Cipta Muri',
            'Pelatihan Pengelolaan Sampah Organik untuk Masyarakat',
            'Kemitraan Strategis dengan Sekolah-Sekolah Setempat',
            'Workshop Daur Ulang Sampah Plastik Jadi Kerajinan',
            'Bank Sampah Cipta Muri Raih Penghargaan Lingkungan',
            'Sosialisasi Program 3R di Kampung Ramah Lingkungan',
            'Lomba Kreativitas Sampah untuk Anak-Anak',
            'Edukasi Lingkungan di Sekolah Dasar Sekitar',
            'Program Pemberdayaan Ekonomi Melalui Bank Sampah',
            'Inovasi Teknologi Pengolahan Sampah Terbaru',
            'Seminar Nasional Pengelolaan Sampah Berkelanjutan',
            'Gerakan Pilah Sampah dari Rumah',
            'Peluncuran Aplikasi Mobile Bank Sampah',
            'Kampanye Zero Waste Lifestyle',
            'Pelatihan Komposting untuk Ibu-Ibu PKK',
            'Program Tukar Sampah dengan Sembako',
            'Kunjungan Studi Banding dari Daerah Lain',
            'Festival Produk Daur Ulang Kreatif',
            'Webinar Ekonomi Sirkular dan Bank Sampah',
            'Launching Program Tabungan Sampah Sekolah',
        ];

        $title = fake()->randomElement($titles);
        $category = fake()->randomElement($categories);
        $status = fake()->randomElement($statuses);

        // Generate content based on category
        $content = $this->generateContentByCategory($category, $title);

        // Generate tags based on category
        $tags = $this->generateTagsByCategory($category);

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . fake()->numberBetween(1, 999),
            'content' => $content,
            'category' => $category,
            'status' => $status,
            'published_at' => $status === 'published' ? fake()->dateTimeBetween('-6 months', 'now') : null,
            'author_id' => User::factory(),
            'tags' => $tags,
            'views_count' => fake()->numberBetween(10, 2000),
            'meta_description' => Str::limit(strip_tags($content), 160),
            'featured_image' => fake()->imageUrl(800, 400, 'nature', true, 'environmental'),
        ];
    }

    /**
     * Generate content based on category.
     */
    private function generateContentByCategory(string $category, string $title): string
    {
        $baseContent = match($category) {
            'program' => [
                '<p>Bank Sampah Digital Cipta Muri dengan bangga mengumumkan program terbaru yang akan membantu masyarakat dalam mengelola sampah dengan lebih efektif dan ramah lingkungan.</p>',
                '<p>Program ini mencakup sistem digital yang memungkinkan nasabah untuk melacak tabungan sampah mereka secara real-time, mendapatkan poin reward, dan menukarkannya dengan berbagai hadiah menarik.</p>',
                '<p>Dengan teknologi terdepan, kami berkomitmen untuk menciptakan lingkungan yang lebih bersih dan sustainable untuk generasi mendatang.</p>'
            ],
            'event' => [
                '<p>Bergabunglah dengan kami dalam acara yang akan dilaksanakan dalam waktu dekat. Acara ini ditujukan untuk meningkatkan kesadaran masyarakat tentang pentingnya pengelolaan sampah yang baik.</p>',
                '<p>Peserta akan mendapatkan pelatihan praktis, workshop interaktif, dan kesempatan networking dengan para ahli lingkungan.</p>',
                '<p>Daftarkan diri Anda sekarang dan jadilah bagian dari gerakan lingkungan yang berkelanjutan!</p>'
            ],
            'announcement' => [
                '<p>Pengumuman penting dari Bank Sampah Digital Cipta Muri untuk seluruh nasabah dan masyarakat.</p>',
                '<p>Informasi ini berkaitan dengan kebijakan terbaru dan perubahan sistem operasional yang akan meningkatkan pelayanan kami.</p>',
                '<p>Untuk informasi lebih lanjut, silakan hubungi kantor kami atau kunjungi website resmi.</p>'
            ],
            'achievement' => [
                '<p>Bank Sampah Digital Cipta Muri meraih pencapaian membanggakan yang menunjukkan komitmen kami terhadap lingkungan.</p>',
                '<p>Prestasi ini merupakan hasil kerja keras seluruh tim dan dukungan masyarakat yang luar biasa.</p>',
                '<p>Kami berterima kasih atas kepercayaan dan akan terus berinovasi untuk memberikan dampak positif yang lebih besar.</p>'
            ],
            'education' => [
                '<p>Edukasi merupakan kunci utama dalam perubahan perilaku masyarakat terhadap pengelolaan sampah yang bertanggung jawab.</p>',
                '<p>Melalui program edukasi ini, kami menyediakan informasi praktis dan mudah dipahami tentang cara mengelola sampah di rumah.</p>',
                '<p>Mari bersama-sama membangun kesadaran lingkungan untuk masa depan yang lebih hijau dan berkelanjutan.</p>'
            ],
            'partnership' => [
                '<p>Bank Sampah Digital Cipta Muri menjalin kemitraan strategis untuk memperluas dampak positif program lingkungan.</p>',
                '<p>Kemitraan ini akan membuka peluang kolaborasi yang lebih luas dalam pengembangan program dan inovasi teknologi.</p>',
                '<p>Bersama mitra, kami optimis dapat mencapai target lingkungan yang ambisius dan berkelanjutan.</p>'
            ],
            default => [
                '<p>Informasi terkini dari Bank Sampah Digital Cipta Muri.</p>',
                '<p>Terus ikuti perkembangan program dan kegiatan kami untuk mendapatkan informasi terbaru.</p>',
                '<p>Terima kasih atas dukungan dan partisipasi aktif Anda dalam program lingkungan.</p>'
            ]
        };

        return implode('', $baseContent);
    }

    /**
     * Generate tags based on category.
     */
    private function generateTagsByCategory(string $category): array
    {
        $baseTags = ['bank sampah', 'lingkungan', 'daur ulang'];
        
        $categoryTags = match($category) {
            'program' => ['program baru', 'inovasi', 'digital'],
            'event' => ['acara', 'workshop', 'pelatihan'],
            'announcement' => ['pengumuman', 'informasi', 'kebijakan'],
            'achievement' => ['prestasi', 'penghargaan', 'pencapaian'],
            'education' => ['edukasi', 'pembelajaran', 'kesadaran'],
            'partnership' => ['kemitraan', 'kolaborasi', 'kerja sama'],
            default => ['update', 'berita']
        };

        return array_merge($baseTags, $categoryTags);
    }

    /**
     * Create published news.
     */
    public function published(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => fake()->dateTimeBetween('-3 months', 'now'),
            'views_count' => fake()->numberBetween(50, 2000),
        ]);
    }

    /**
     * Create draft news.
     */
    public function draft(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'draft',
            'published_at' => null,
            'views_count' => 0,
        ]);
    }

    /**
     * Create popular news.
     */
    public function popular(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => fake()->dateTimeBetween('-2 months', '-1 week'),
            'views_count' => fake()->numberBetween(1000, 5000),
        ]);
    }

    /**
     * Create recent news.
     */
    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'published',
            'published_at' => fake()->dateTimeBetween('-2 weeks', 'now'),
            'views_count' => fake()->numberBetween(10, 500),
        ]);
    }

    /**
     * Create program category news.
     */
    public function program(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'program',
            'tags' => ['bank sampah', 'program baru', 'inovasi', 'digital', 'lingkungan'],
        ]);
    }

    /**
     * Create event category news.
     */
    public function event(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'event',
            'tags' => ['acara', 'workshop', 'pelatihan', 'edukasi', 'masyarakat'],
        ]);
    }

    /**
     * Create achievement category news.
     */
    public function achievement(): static
    {
        return $this->state(fn (array $attributes) => [
            'category' => 'achievement',
            'tags' => ['prestasi', 'penghargaan', 'pencapaian', 'bank sampah', 'lingkungan'],
        ]);
    }
}