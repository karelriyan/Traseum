<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class NewsController extends Controller
{
    public function index()
    {
        // Sample news data - nanti bisa diganti dengan data dari database
        $news = [
            [
                'id' => 1,
                'title' => 'Bank Sampah Cipta Muri Raih Penghargaan Bank Sampah Terbaik Jawa Tengah 2024',
                'excerpt' => 'Prestasi membanggakan diraih Bank Sampah Cipta Muri sebagai Bank Sampah Terbaik tingkat Provinsi Jawa Tengah berkat inovasi sistem digital dan dampak positif terhadap lingkungan.',
                'content' => 'Dalam event tahunan penghargaan lingkungan hidup...',
                'image' => '/images/news/news1.jpg',
                'category' => 'Penghargaan',
                'categoryColor' => 'bg-green-500',
                'author' => 'Tim Redaksi',
                'publishedAt' => '2024-08-15',
                'views' => 1234,
                'readTime' => '5 menit'
            ],
            [
                'id' => 2,
                'title' => 'Workshop Edukasi Lingkungan untuk 500 Siswa SD di Kabupaten Cilacap',
                'excerpt' => 'Program edukasi lingkungan yang diselenggarakan Bank Sampah Cipta Muri berhasil menjangkau 500 siswa SD dengan materi pengelolaan sampah dan gaya hidup berkelanjutan.',
                'content' => 'Workshop edukasi lingkungan yang diselenggarakan...',
                'image' => '/images/news/news2.jpg',
                'category' => 'Kegiatan',
                'categoryColor' => 'bg-blue-500',
                'author' => 'Sari Dewi',
                'publishedAt' => '2024-08-10',
                'views' => 856,
                'readTime' => '3 menit'
            ],
            [
                'id' => 3,
                'title' => 'Peluncuran Aplikasi Mobile "EcoBank Cipta Muri" dengan Fitur AI dan Blockchain',
                'excerpt' => 'Inovasi terdepan dalam pengelolaan sampah digital dengan meluncurkan aplikasi mobile yang dilengkapi teknologi AI untuk identifikasi jenis sampah dan blockchain untuk transparansi transaksi.',
                'content' => 'Aplikasi mobile EcoBank Cipta Muri resmi diluncurkan...',
                'image' => '/images/news/news3.jpg',
                'category' => 'Inovasi',
                'categoryColor' => 'bg-purple-500',
                'author' => 'Budi Santoso',
                'publishedAt' => '2024-08-05',
                'views' => 2100,
                'readTime' => '7 menit'
            ],
            [
                'id' => 4,
                'title' => 'Kemitraan dengan 50 UMKM Lokal untuk Program Tukar Poin Produk Ramah Lingkungan',
                'excerpt' => 'Ekspansi jaringan kemitraan dengan 50 UMKM lokal memungkinkan nasabah menukarkan poin tabungan dengan berbagai produk ramah lingkungan dan mendukung ekonomi lokal.',
                'content' => 'Program kemitraan strategis dengan UMKM lokal...',
                'image' => '/images/news/news4.jpg',
                'category' => 'Kemitraan',
                'categoryColor' => 'bg-yellow-500',
                'author' => 'Rina Oktavia',
                'publishedAt' => '2024-07-28',
                'views' => 934,
                'readTime' => '4 menit'
            ],
            [
                'id' => 5,
                'title' => 'Pelatihan Pengelolaan Sampah untuk 20 Desa se-Kabupaten Cilacap',
                'excerpt' => 'Program transfer knowledge kepada 20 desa di Kabupaten Cilacap untuk mendirikan bank sampah mandiri dengan sistem pengelolaan yang sustainable dan profitable.',
                'content' => 'Pelatihan komprehensif pengelolaan sampah...',
                'image' => '/images/news/news5.jpg',
                'category' => 'Pelatihan',
                'categoryColor' => 'bg-indigo-500',
                'author' => 'Ahmad Wijaya',
                'publishedAt' => '2024-07-20',
                'views' => 1456,
                'readTime' => '6 menit'
            ],
            [
                'id' => 6,
                'title' => 'Milestone 1.250 Anggota Aktif dan Total Tabungan Rp 45 Miliar',
                'excerpt' => 'Pencapaian luar biasa dengan 1.250 anggota aktif dan total tabungan mencapai Rp 45 miliar, membuktikan kepercayaan masyarakat terhadap sistem bank sampah digital.',
                'content' => 'Pencapaian milestone yang membanggakan...',
                'image' => '/images/news/news6.jpg',
                'category' => 'Milestone',
                'categoryColor' => 'bg-red-500',
                'author' => 'Lisa Permata',
                'publishedAt' => '2024-07-15',
                'views' => 1789,
                'readTime' => '4 menit'
            ]
        ];

        $categories = ['Semua', 'Penghargaan', 'Kegiatan', 'Inovasi', 'Kemitraan', 'Pelatihan', 'Milestone'];

        return Inertia::render('News', [
            'news' => $news,
            'categories' => $categories
        ]);
    }

    public function show($id)
    {
        // Sample news detail data - nanti bisa diganti dengan data dari database
        $news = [
            'id' => (int) $id,
            'title' => 'Bank Sampah Cipta Muri Raih Penghargaan Bank Sampah Terbaik Jawa Tengah 2024',
            'content' => '
                <p>Dalam event tahunan penghargaan lingkungan hidup yang diselenggarakan oleh Pemerintah Provinsi Jawa Tengah, Bank Sampah Cipta Muri berhasil meraih prestasi membanggakan sebagai Bank Sampah Terbaik tingkat Provinsi Jawa Tengah tahun 2024.</p>
                
                <p>Penghargaan ini diberikan berdasarkan penilaian komprehensif terhadap beberapa aspek, antara lain:</p>
                
                <h3>Kriteria Penilaian</h3>
                <ul>
                    <li><strong>Inovasi Teknologi:</strong> Implementasi sistem digital dan aplikasi mobile yang memudahkan nasabah dalam bertransaksi</li>
                    <li><strong>Dampak Lingkungan:</strong> Kontribusi nyata dalam pengurangan sampah dan peningkatan kesadaran masyarakat</li>
                    <li><strong>Keberlanjutan Program:</strong> Konsistensi dalam menjalankan program edukasi dan pemberdayaan masyarakat</li>
                    <li><strong>Transparansi Keuangan:</strong> Sistem pelaporan yang akuntabel dan mudah diakses publik</li>
                    <li><strong>Kemitraan Strategis:</strong> Kolaborasi dengan berbagai pihak untuk memperluas dampak positif</li>
                </ul>
                
                <h3>Pencapaian Luar Biasa</h3>
                <p>Sepanjang tahun 2024, Bank Sampah Cipta Muri telah mencatatkan berbagai pencapaian signifikan:</p>
                
                <ul>
                    <li>Meningkatkan jumlah anggota aktif menjadi 1.250 orang</li>
                    <li>Mengolah lebih dari 150 ton sampah anorganik</li>
                    <li>Mencapai total tabungan nasabah sebesar Rp 45 miliar</li>
                    <li>Melaksanakan 25 program edukasi lingkungan</li>
                    <li>Bermitra dengan 50 UMKM lokal</li>
                </ul>
                
                <h3>Testimoni Kepala Dinas Lingkungan Hidup</h3>
                <blockquote>
                    "Bank Sampah Cipta Muri telah membuktikan bahwa pengelolaan sampah dapat dilakukan secara modern, efisien, dan menguntungkan. Inovasi teknologi yang mereka terapkan menjadi contoh bagi bank sampah lainnya di Jawa Tengah."
                </blockquote>
                <cite>- Drs. Bambang Sutrisno, M.Si., Kepala Dinas Lingkungan Hidup Provinsi Jawa Tengah</cite>
                
                <h3>Komitmen Berkelanjutan</h3>
                <p>Sebagai penerima penghargaan, Bank Sampah Cipta Muri berkomitmen untuk terus berinovasi dan memperluas jangkauan program. Beberapa rencana strategis ke depan meliputi:</p>
                
                <ul>
                    <li>Pengembangan fitur AI dalam aplikasi mobile untuk identifikasi jenis sampah</li>
                    <li>Ekspansi program ke 10 desa sekitar</li>
                    <li>Implementasi teknologi blockchain untuk transparansi transaksi</li>
                    <li>Kemitraan dengan institusi pendidikan untuk program penelitian</li>
                </ul>
                
                <p>Penghargaan ini bukan hanya milik Bank Sampah Cipta Muri, tetapi juga seluruh masyarakat Desa Muntang yang telah bersama-sama mendukung visi lingkungan berkelanjutan. Dengan dedikasi dan inovasi berkelanjutan, kami optimis dapat memberikan kontribusi yang lebih besar lagi untuk masa depan yang lebih hijau.</p>
            ',
            'excerpt' => 'Prestasi membanggakan diraih Bank Sampah Cipta Muri sebagai Bank Sampah Terbaik tingkat Provinsi Jawa Tengah berkat inovasi sistem digital dan dampak positif terhadap lingkungan.',
            'image' => '/images/news/news1.jpg',
            'category' => 'Penghargaan',
            'categoryColor' => 'bg-green-500',
            'author' => 'Tim Redaksi',
            'publishedAt' => '2024-08-15',
            'views' => 1234,
            'readTime' => '5 menit',
            'tags' => ['penghargaan', 'inovasi', 'lingkungan', 'teknologi', 'jawa-tengah']
        ];

        $relatedNews = [
            [
                'id' => 2,
                'title' => 'Workshop Edukasi Lingkungan untuk 500 Siswa SD',
                'excerpt' => 'Program edukasi lingkungan yang diselenggarakan Bank Sampah Cipta Muri berhasil menjangkau 500 siswa SD...',
                'image' => '/images/news/news2.jpg',
                'category' => 'Kegiatan',
                'publishedAt' => '2024-08-10'
            ],
            [
                'id' => 3,
                'title' => 'Peluncuran Aplikasi Mobile "EcoBank Cipta Muri"',
                'excerpt' => 'Inovasi terdepan dalam pengelolaan sampah digital dengan meluncurkan aplikasi mobile...',
                'image' => '/images/news/news3.jpg',
                'category' => 'Inovasi',
                'publishedAt' => '2024-08-05'
            ],
            [
                'id' => 4,
                'title' => 'Kemitraan dengan 50 UMKM Lokal',
                'excerpt' => 'Ekspansi jaringan kemitraan dengan 50 UMKM lokal memungkinkan nasabah menukarkan poin...',
                'image' => '/images/news/news4.jpg',
                'category' => 'Kemitraan',
                'publishedAt' => '2024-07-28'
            ]
        ];

        return Inertia::render('NewsDetail', [
            'news' => $news,
            'relatedNews' => $relatedNews
        ]);
    }
}
