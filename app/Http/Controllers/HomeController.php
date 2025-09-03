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
            'members' => 1250,
            'recycled_tons' => 15.8,
            'savings_total' => 45750000,
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
                'name' => 'Ibu Sari Wijaya',
                'role' => 'Anggota Bank Sampah',
                'content' => 'Dengan Bank Sampah Cipta Muri, sampah rumah tangga saya bisa jadi tabungan. Sangat membantu ekonomi keluarga.',
                'avatar' => 'https://images.unsplash.com/photo-1494790108755-2616c4f55d16?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=250&q=80',
                'rating' => 5
            ],
            [
                'name' => 'Bapak Ahmad Fauzi',
                'role' => 'Kepala RT 03',
                'content' => 'Lingkungan RT kami jadi lebih bersih sejak ada Bank Sampah. Warga juga lebih sadar pentingnya daur ulang.',
                'avatar' => 'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=250&q=80',
                'rating' => 5
            ],
            [
                'name' => 'Ibu Maya Sinta',
                'role' => 'Pengusaha UMKM',
                'content' => 'Kemitraan dengan Bank Sampah membantu saya mendapat bahan baku berkualitas untuk kerajinan daur ulang.',
                'avatar' => 'https://images.unsplash.com/photo-1438761681033-6461ffad8d80?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=250&q=80',
                'rating' => 5
            ],
            [
                'name' => 'Bapak Joko Susanto',
                'role' => 'Petugas Pengangkut',
                'content' => 'Sistem digital Bank Sampah memudahkan saya dalam mencatat setiap setoran sampah dari warga. Sangat efisien!',
                'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=250&q=80',
                'rating' => 5
            ],
            [
                'name' => 'Ibu Fitri Handayani',
                'role' => 'Ibu Rumah Tangga',
                'content' => 'Anak-anak jadi lebih peduli lingkungan sejak ikut program Bank Sampah. Kebiasaan baik untuk masa depan.',
                'avatar' => 'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=250&q=80',
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
