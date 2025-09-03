import Button from '@/components/Button';
import NewsCard from '@/components/News/NewsCard';
import MainLayout from '@/layouts/MainLayout';
import { Head } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { ArrowRight, Filter, Search, TrendingUp } from 'lucide-react';
import { useState } from 'react';

interface NewsItem {
    id: number;
    title: string;
    excerpt: string;
    content: string;
    image: string;
    category: string;
    categoryColor: string;
    author: string;
    publishedAt: string;
    views: number;
    readTime: string;
}

interface NewsProps {
    news: NewsItem[];
    categories: string[];
}

export default function News({ news = [], categories = [] }: NewsProps) {
    const [searchTerm, setSearchTerm] = useState('');
    const [selectedCategory, setSelectedCategory] = useState('Semua');
    const [currentPage, setCurrentPage] = useState(1);
    const itemsPerPage = 6;

    // Filter news based on search and category
    const filteredNews = news.filter((item) => {
        const matchesSearch =
            item.title.toLowerCase().includes(searchTerm.toLowerCase()) || item.excerpt.toLowerCase().includes(searchTerm.toLowerCase());
        const matchesCategory = selectedCategory === 'Semua' || item.category === selectedCategory;
        return matchesSearch && matchesCategory;
    });

    // Pagination
    const totalPages = Math.ceil(filteredNews.length / itemsPerPage);
    const startIndex = (currentPage - 1) * itemsPerPage;
    const currentNews = filteredNews.slice(startIndex, startIndex + itemsPerPage);

    // Sample data jika tidak ada data dari props
    const sampleNews: NewsItem[] = [
        {
            id: 1,
            title: 'Bank Sampah Cipta Muri Raih Penghargaan Bank Sampah Terbaik Jawa Tengah 2024',
            excerpt:
                'Prestasi membanggakan diraih Bank Sampah Cipta Muri sebagai Bank Sampah Terbaik tingkat Provinsi Jawa Tengah berkat inovasi sistem digital dan dampak positif terhadap lingkungan.',
            content: 'Dalam event tahunan penghargaan lingkungan hidup...',
            image: '/images/news/news1.jpg',
            category: 'Penghargaan',
            categoryColor: 'bg-green-500',
            author: 'Tim Redaksi',
            publishedAt: '2024-08-15',
            views: 1234,
            readTime: '5 menit',
        },
        {
            id: 2,
            title: 'Workshop Edukasi Lingkungan untuk 500 Siswa SD di Kabupaten Cilacap',
            excerpt:
                'Program edukasi lingkungan yang diselenggarakan Bank Sampah Cipta Muri berhasil menjangkau 500 siswa SD dengan materi pengelolaan sampah dan gaya hidup berkelanjutan.',
            content: 'Workshop edukasi lingkungan yang diselenggarakan...',
            image: '/images/news/news2.jpg',
            category: 'Kegiatan',
            categoryColor: 'bg-blue-500',
            author: 'Sari Dewi',
            publishedAt: '2024-08-10',
            views: 856,
            readTime: '3 menit',
        },
        {
            id: 3,
            title: "Peluncuran Aplikasi Mobile 'EcoBank Cipta Muri' dengan Fitur AI dan Blockchain",
            excerpt:
                'Inovasi terdepan dalam pengelolaan sampah digital dengan meluncurkan aplikasi mobile yang dilengkapi teknologi AI untuk identifikasi jenis sampah dan blockchain untuk transparansi transaksi.',
            content: 'Aplikasi mobile EcoBank Cipta Muri resmi diluncurkan...',
            image: '/images/news/news3.jpg',
            category: 'Inovasi',
            categoryColor: 'bg-purple-500',
            author: 'Budi Santoso',
            publishedAt: '2024-08-05',
            views: 2100,
            readTime: '7 menit',
        },
        {
            id: 4,
            title: 'Kemitraan dengan 50 UMKM Lokal untuk Program Tukar Poin Produk Ramah Lingkungan',
            excerpt:
                'Ekspansi jaringan kemitraan dengan 50 UMKM lokal memungkinkan nasabah menukarkan poin tabungan dengan berbagai produk ramah lingkungan dan mendukung ekonomi lokal.',
            content: 'Program kemitraan strategis dengan UMKM lokal...',
            image: '/images/news/news4.jpg',
            category: 'Kemitraan',
            categoryColor: 'bg-yellow-500',
            author: 'Rina Oktavia',
            publishedAt: '2024-07-28',
            views: 934,
            readTime: '4 menit',
        },
        {
            id: 5,
            title: 'Pelatihan Pengelolaan Sampah untuk 20 Desa se-Kabupaten Cilacap',
            excerpt:
                'Program transfer knowledge kepada 20 desa di Kabupaten Cilacap untuk mendirikan bank sampah mandiri dengan sistem pengelolaan yang sustainable dan profitable.',
            content: 'Pelatihan komprehensif pengelolaan sampah...',
            image: '/images/news/news5.jpg',
            category: 'Pelatihan',
            categoryColor: 'bg-indigo-500',
            author: 'Ahmad Wijaya',
            publishedAt: '2024-07-20',
            views: 1456,
            readTime: '6 menit',
        },
        {
            id: 6,
            title: 'Milestone 1.250 Anggota Aktif dan Total Tabungan Rp 45 Miliar',
            excerpt:
                'Pencapaian luar biasa dengan 1.250 anggota aktif dan total tabungan mencapai Rp 45 miliar, membuktikan kepercayaan masyarakat terhadap sistem bank sampah digital.',
            content: 'Pencapaian milestone yang membanggakan...',
            image: '/images/news/news6.jpg',
            category: 'Milestone',
            categoryColor: 'bg-red-500',
            author: 'Lisa Permata',
            publishedAt: '2024-07-15',
            views: 1789,
            readTime: '4 menit',
        },
    ];

    const displayNews = news.length > 0 ? currentNews : sampleNews;
    const displayCategories =
        categories.length > 0 ? categories : ['Semua', 'Penghargaan', 'Kegiatan', 'Inovasi', 'Kemitraan', 'Pelatihan', 'Milestone'];

    const formatDate = (dateString: string) => {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        });
    };

    return (
        <MainLayout>
            <Head>
                <title>Berita & Publikasi - Bank Sampah Cipta Muri</title>
                <meta
                    name="description"
                    content="Berita terkini dan publikasi Bank Sampah Cipta Muri tentang kegiatan, inovasi, dan pencapaian dalam pengelolaan sampah berkelanjutan."
                />
            </Head>

            {/* Hero Section */}
            <section className="relative bg-gradient-to-br from-green-600 via-green-700 to-green-800 py-20 text-white">
                <div className="absolute inset-0 bg-black/20"></div>
                <div className="container-custom relative z-10">
                    <motion.div
                        className="mx-auto max-w-4xl text-center"
                        initial={{ opacity: 0, y: 30 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.8 }}
                    >
                        <motion.h1
                            className="mb-6 text-4xl font-bold md:text-5xl lg:text-6xl"
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.8, delay: 0.2 }}
                        >
                            Berita & Publikasi
                        </motion.h1>
                        <motion.p
                            className="mb-8 text-xl leading-relaxed text-green-100"
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.8, delay: 0.4 }}
                        >
                            Ikuti perkembangan terbaru Bank Sampah Cipta Muri, prestasi yang diraih, dan berbagai kegiatan yang telah dilaksanakan
                            untuk menciptakan lingkungan yang lebih berkelanjutan.
                        </motion.p>
                    </motion.div>
                </div>
            </section>

            {/* Search and Filter Section */}
            <section className="section-padding bg-gray-50">
                <div className="container-custom">
                    <motion.div
                        className="mx-auto max-w-4xl"
                        initial={{ opacity: 0, y: 20 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.6 }}
                    >
                        <div className="mb-8 rounded-xl bg-white p-6 shadow-lg">
                            <div className="grid gap-6 md:grid-cols-2">
                                {/* Search */}
                                <div className="relative">
                                    <Search className="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" />
                                    <input
                                        type="text"
                                        placeholder="Cari berita..."
                                        value={searchTerm}
                                        onChange={(e) => setSearchTerm(e.target.value)}
                                        className="w-full rounded-lg border border-gray-300 bg-gray-50 py-3 pl-10 pr-4 text-gray-900 placeholder-gray-500 focus:border-green-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-500/20"
                                    />
                                </div>

                                {/* Category Filter */}
                                <div className="relative">
                                    <Filter className="absolute left-3 top-1/2 h-5 w-5 -translate-y-1/2 text-gray-400" />
                                    <select
                                        value={selectedCategory}
                                        onChange={(e) => setSelectedCategory(e.target.value)}
                                        className="w-full appearance-none rounded-lg border border-gray-300 bg-gray-50 py-3 pl-10 pr-8 text-gray-900 focus:border-green-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-green-500/20"
                                    >
                                        {displayCategories.map((category) => (
                                            <option key={category} value={category}>
                                                {category}
                                            </option>
                                        ))}
                                    </select>
                                </div>
                            </div>
                        </div>

                        {/* Results Info */}
                        <div className="mb-6 text-gray-600">
                            Menampilkan {filteredNews.length} dari {news.length || sampleNews.length} berita
                        </div>
                    </motion.div>
                </div>
            </section>

            {/* Featured News */}
            {displayNews.length > 0 && (
                <section className="section-padding bg-white">
                    <div className="container-custom">
                        <motion.div
                            className="mb-8 flex items-center space-x-3"
                            initial={{ opacity: 0, x: -20 }}
                            animate={{ opacity: 1, x: 0 }}
                            transition={{ duration: 0.6 }}
                        >
                            <TrendingUp className="h-6 w-6 text-green-600" />
                            <h2 className="text-2xl font-bold text-gray-900">Berita Utama</h2>
                        </motion.div>

                        <NewsCard {...displayNews[0]} featured={true} delay={0.1} />
                    </div>
                </section>
            )}

            {/* News Grid */}
            <section className="section-padding bg-gray-50">
                <div className="container-custom">
                    {displayNews.length > 1 && (
                        <motion.h2
                            className="mb-8 text-2xl font-bold text-gray-900"
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.6 }}
                        >
                            Berita Lainnya
                        </motion.h2>
                    )}

                    <div className="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
                        {displayNews.slice(1).map((item, index) => (
                            <NewsCard key={item.id} {...item} delay={index * 0.1} />
                        ))}
                    </div>

                    {/* Pagination */}
                    {totalPages > 1 && (
                        <motion.div
                            className="mt-12 flex justify-center"
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.6, delay: 0.3 }}
                        >
                            <div className="flex items-center space-x-2">
                                <button
                                    onClick={() => setCurrentPage((prev) => Math.max(prev - 1, 1))}
                                    disabled={currentPage === 1}
                                    className="rounded-lg border border-gray-300 px-4 py-2 text-gray-700 transition-colors hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    Sebelumnya
                                </button>

                                {Array.from({ length: totalPages }, (_, i) => i + 1).map((page) => (
                                    <button
                                        key={page}
                                        onClick={() => setCurrentPage(page)}
                                        className={`rounded-lg px-4 py-2 transition-colors ${
                                            currentPage === page ? 'bg-green-600 text-white' : 'border border-gray-300 text-gray-700 hover:bg-gray-50'
                                        }`}
                                    >
                                        {page}
                                    </button>
                                ))}

                                <button
                                    onClick={() => setCurrentPage((prev) => Math.min(prev + 1, totalPages))}
                                    disabled={currentPage === totalPages}
                                    className="rounded-lg border border-gray-300 px-4 py-2 text-gray-700 transition-colors hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    Selanjutnya
                                </button>
                            </div>
                        </motion.div>
                    )}

                    {/* No Results */}
                    {filteredNews.length === 0 && (
                        <motion.div
                            className="py-16 text-center"
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.6 }}
                        >
                            <div className="mx-auto mb-4 h-16 w-16 rounded-full bg-gray-100 p-4">
                                <Search className="h-8 w-8 text-gray-400" />
                            </div>
                            <h3 className="mb-2 text-lg font-semibold text-gray-900">Tidak ada berita ditemukan</h3>
                            <p className="text-gray-600">Coba ubah kata kunci pencarian atau filter kategori</p>
                        </motion.div>
                    )}
                </div>
            </section>

            {/* CTA Section */}
            <section className="section-padding bg-gradient-to-r from-green-600 to-green-700 text-white">
                <div className="container-custom">
                    <motion.div
                        className="mx-auto max-w-3xl text-center"
                        initial={{ opacity: 0, y: 30 }}
                        whileInView={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.8 }}
                        viewport={{ once: true }}
                    >
                        <h2 className="mb-6 text-3xl font-bold md:text-4xl">Ingin Mendapatkan Update Terbaru?</h2>
                        <p className="mb-8 text-xl leading-relaxed text-green-100">
                            Bergabunglah dengan komunitas Bank Sampah Cipta Muri dan dapatkan informasi terkini tentang program, kegiatan, dan inovasi
                            terbaru kami.
                        </p>
                        <div className="flex flex-col gap-4 sm:flex-row sm:justify-center">
                            <Button href="/register" size="lg" className="bg-white text-green-600 hover:bg-gray-100">
                                Daftar Sekarang
                                <ArrowRight className="ml-2 h-5 w-5" />
                            </Button>
                            <Button href="/#kontak" variant="outline" size="lg" className="border-white text-white hover:bg-white/10">
                                Hubungi Kami
                            </Button>
                        </div>
                    </motion.div>
                </div>
            </section>
        </MainLayout>
    );
}
