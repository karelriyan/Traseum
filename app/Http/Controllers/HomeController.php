<?php

namespace App\Http\Controllers;

//use App\Models\Nasabah;
use App\Models\SetorSampah;
use App\Models\SaldoTransaction;
use App\Models\News;
//use App\Models\Umkm;//
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
        /**
     * Display the landing page with statistics and content
     */
    public function index()
    {
        // Mock stats data untuk development
        $stats = [
            'members' => 176, // Ubah sesuai kebutuhan
            'recycled_tons' => 1, // Ubah sesuai kebutuhan
            'savings_total' => 2500000, // Ubah sesuai kebutuhan
        ];
        

        // Mock data untuk programs
        $programs = [
            [
                'icon' => 'GraduationCap',
                'title' => 'Edukasi Lingkungan',
                'description' => 'Workshop dan pelatihan tentang pengelolaan sampah yang baik dan ramah lingkungan.',
                'features' => [
                    'Workshop rutin setiap minggu',
                    'Materi edukatif terlengkap',
                    'Sertifikat kelulusan',
                    'Akses seumur hidup'
                ]
            ],
            [
                'icon' => 'CreditCard',
                'title' => 'Tabungan Digital',
                'description' => 'Sistem tabungan modern yang memudahkan anggota mengelola hasil dari sampah.',
                'features' => [
                    'Sistem digital terintegrasi',
                    'Laporan transaksi real-time',
                    'Keamanan data terjamin',
                    'Akses 24/7'
                ]
            ],
            [
                'icon' => 'Smartphone',
                'title' => 'Aplikasi Mobile',
                'description' => 'Akses mudah melalui smartphone untuk monitoring dan transaksi kapan saja.',
                'features' => [
                    'Interface user-friendly',
                    'Notifikasi real-time',
                    'Tracking otomatis',
                    'Support multi-platform'
                ]
            ],
            [
                'icon' => 'Store',
                'title' => 'Kemitraan UMKM',
                'description' => 'Mendukung usaha mikro kecil menengah melalui hasil olahan sampah.',
                'features' => [
                    'Jaringan UMKM luas',
                    'Harga bersaing',
                    'Kualitas terjamin',
                    'Pembayaran tepat waktu'
                ]
            ]
        ];

        // Mock data untuk testimonials
        $testimonials = [
            [
                'name' => 'Ibu Harlina',
                'role' => 'Anggota Bank Sampah',
                'content' => 'Dengan Bank Sampah Cipta Muri, sampah rumah tangga saya bisa jadi tabungan. Sangat membantu ekonomi keluarga.',
                'avatar' => 'https://limbahpustaka.com/storage/public/01JXW9ENT3KQK1A2YVKDGZGRF4.jpeg',
                'rating' => 5
            ],
            [
                'name' => 'Bapak Moh. Arif Budiato, S.Pt',
                'role' => 'Kepala Desa Muntang',
                'content' => 'Program Bank Sampah Cipta Muri telah meningkatkan kesadaran warga akan pentingnya pengelolaan sampah. Desa kami menjadi lebih bersih dan mandiri.',
                'avatar' => 'https://www.muntang.berdesa.id/desa/upload/user_pict/c1dHz_1751856355757708.png',
                'rating' => 5
            ],
            [
                'name' => 'Ibu Puji Rahayu',
                'role' => 'Pengusaha UMKM',
                'content' => 'Kemitraan dengan Bank Sampah membantu saya mendapat bahan baku berkualitas untuk kerajinan daur ulang.',
                'avatar' => 'https://limbahpustaka.com/storage/public/01JXW97KZZVDWNAVDTABWPJZ6Y.jpeg',
                'rating' => 5
            ],
            [
                'name' => 'Bapak Agustinus Suryanto, S.Sos',
                'role' => 'Ketua RT.8',
                'content' => 'Sebagai ketua RT, saya melihat langsung bagaimana Bank Sampah Cipta Muri mengubah perilaku warga. Lingkungan jadi lebih bersih dan warga termotivasi!',
                'avatar' => 'https://limbahpustaka.com/storage/public/01JXW8H6Y6ARTT04J6K9137W3F.jpeg',
                'rating' => 5
            ],
            [
                'name' => 'Ibu Adimah',
                'role' => 'Ibu Rumah Tangga',
                'content' => 'Anak-anak jadi lebih peduli lingkungan sejak ikut program Bank Sampah. Kebiasaan baik untuk masa depan.',
                'avatar' => 'https://limbahpustaka.com/storage/public/01JXW8V949DJ70AA7DHCQBZ18C.jpeg',
                'rating' => 5
            ],
            [
                'name' => 'Bapak Rudi Hartono',
                'role' => 'Koordinator Lingkungan',
                'content' => 'Bank Sampah Cipta Muri benar-benar mengubah paradigma masyarakat tentang sampah. Dari beban jadi berkah!',
                'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=250&q=80',
                'rating' => 5
            ]
        ];

        // Get latest news for homepage
        $latestNews = Cache::remember('homepage_latest_news', 3600, function () {
            return News::published()
                ->with('author')
                ->orderBy('published_at', 'desc')
                ->take(6)
                ->get();
        });

        return Inertia::render('Home', [
            'stats' => $stats,
            'programs' => $programs,
            'testimonials' => $testimonials,
            'latestNews' => $latestNews
        ]);
    }
}
