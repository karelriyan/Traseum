import Button from '@/components/Button';
import MainLayout from '@/layouts/MainLayout';
import { Head } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { ArrowLeft, Calendar, Clock, Copy, Eye, Facebook, Linkedin, Share2, Tag, Twitter, User } from 'lucide-react';
import { useState } from 'react';

interface NewsDetailProps {
    news: {
        id: number;
        title: string;
        content: string;
        excerpt: string;
        image: string;
        category: string;
        categoryColor: string;
        author: string;
        publishedAt: string;
        views: number;
        readTime: string;
        tags: string[];
    };
    relatedNews: Array<{
        id: number;
        title: string;
        excerpt: string;
        image: string;
        category: string;
        publishedAt: string;
    }>;
}

export default function NewsDetail({ news, relatedNews = [] }: NewsDetailProps) {
    const [shareMenuOpen, setShareMenuOpen] = useState(false);
    const [copySuccess, setCopySuccess] = useState(false);

    // Sample data jika tidak ada data dari props
    const sampleNews = {
        id: 1,
        title: 'Bank Sampah Cipta Muri Raih Penghargaan Bank Sampah Terbaik Jawa Tengah 2024',
        content: `
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
        `,
        excerpt:
            'Prestasi membanggakan diraih Bank Sampah Cipta Muri sebagai Bank Sampah Terbaik tingkat Provinsi Jawa Tengah berkat inovasi sistem digital dan dampak positif terhadap lingkungan.',
        image: '/images/news/news1.jpg',
        category: 'Penghargaan',
        categoryColor: 'bg-green-500',
        author: 'Tim Redaksi',
        publishedAt: '2024-08-15',
        views: 1234,
        readTime: '5 menit',
        tags: ['penghargaan', 'inovasi', 'lingkungan', 'teknologi', 'jawa-tengah'],
    };

    const sampleRelatedNews = [
        {
            id: 2,
            title: 'Workshop Edukasi Lingkungan untuk 500 Siswa SD',
            excerpt: 'Program edukasi lingkungan yang diselenggarakan Bank Sampah Cipta Muri berhasil menjangkau 500 siswa SD...',
            image: '/images/news/news2.jpg',
            category: 'Kegiatan',
            publishedAt: '2024-08-10',
        },
        {
            id: 3,
            title: "Peluncuran Aplikasi Mobile 'EcoBank Cipta Muri'",
            excerpt: 'Inovasi terdepan dalam pengelolaan sampah digital dengan meluncurkan aplikasi mobile...',
            image: '/images/news/news3.jpg',
            category: 'Inovasi',
            publishedAt: '2024-08-05',
        },
        {
            id: 4,
            title: 'Kemitraan dengan 50 UMKM Lokal',
            excerpt: 'Ekspansi jaringan kemitraan dengan 50 UMKM lokal memungkinkan nasabah menukarkan poin...',
            image: '/images/news/news4.jpg',
            category: 'Kemitraan',
            publishedAt: '2024-07-28',
        },
    ];

    const displayNews = news || sampleNews;
    const displayRelatedNews = relatedNews.length > 0 ? relatedNews : sampleRelatedNews;

    const formatDate = (dateString: string) => {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        });
    };

    const shareUrl = typeof window !== 'undefined' ? window.location.href : '';

    const copyToClipboard = async () => {
        try {
            await navigator.clipboard.writeText(shareUrl);
            setCopySuccess(true);
            setTimeout(() => setCopySuccess(false), 2000);
        } catch (err) {
            console.error('Failed to copy: ', err);
        }
    };

    const shareLinks = {
        facebook: `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(shareUrl)}`,
        twitter: `https://twitter.com/intent/tweet?text=${encodeURIComponent(displayNews.title)}&url=${encodeURIComponent(shareUrl)}`,
        linkedin: `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(shareUrl)}`,
    };

    return (
        <MainLayout>
            <Head>
                <title>{displayNews.title} - Bank Sampah Cipta Muri</title>
                <meta name="description" content={displayNews.excerpt} />
                <meta property="og:title" content={displayNews.title} />
                <meta property="og:description" content={displayNews.excerpt} />
                <meta property="og:image" content={displayNews.image} />
                <meta property="og:url" content={shareUrl} />
                <meta name="twitter:card" content="summary_large_image" />
            </Head>

            {/* Breadcrumb */}
            <section className="section-padding bg-gray-50 py-8">
                <div className="container-custom">
                    <nav className="flex items-center space-x-2 text-sm text-gray-600">
                        <a href="/" className="hover:text-green-600">
                            Home
                        </a>
                        <span>/</span>
                        <a href="/news" className="hover:text-green-600">
                            Berita
                        </a>
                        <span>/</span>
                        <span className="text-gray-900">{displayNews.category}</span>
                    </nav>
                </div>
            </section>

            {/* Article Header */}
            <section className="section-padding bg-white">
                <div className="container-custom">
                    <div className="mx-auto max-w-4xl">
                        {/* Back Button */}
                        <motion.div className="mb-6" initial={{ opacity: 0, x: -20 }} animate={{ opacity: 1, x: 0 }} transition={{ duration: 0.6 }}>
                            <Button href="/news" variant="outline" size="sm" className="group">
                                <ArrowLeft className="mr-2 h-4 w-4 transition-transform group-hover:-translate-x-1" />
                                Kembali ke Berita
                            </Button>
                        </motion.div>

                        {/* Category Badge */}
                        <motion.div
                            className="mb-4"
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.6, delay: 0.1 }}
                        >
                            <span className={`inline-block rounded-full ${displayNews.categoryColor} px-4 py-2 text-sm font-medium text-white`}>
                                {displayNews.category}
                            </span>
                        </motion.div>

                        {/* Title */}
                        <motion.h1
                            className="mb-6 text-3xl font-bold text-gray-900 md:text-4xl lg:text-5xl"
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.6, delay: 0.2 }}
                        >
                            {displayNews.title}
                        </motion.h1>

                        {/* Meta Info */}
                        <motion.div
                            className="mb-8 flex flex-wrap items-center gap-6 border-b border-gray-200 pb-6 text-sm text-gray-600"
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.6, delay: 0.3 }}
                        >
                            <div className="flex items-center space-x-2">
                                <User className="h-4 w-4" />
                                <span>Oleh {displayNews.author}</span>
                            </div>
                            <div className="flex items-center space-x-2">
                                <Calendar className="h-4 w-4" />
                                <span>{formatDate(displayNews.publishedAt)}</span>
                            </div>
                            <div className="flex items-center space-x-2">
                                <Clock className="h-4 w-4" />
                                <span>{displayNews.readTime}</span>
                            </div>
                            <div className="flex items-center space-x-2">
                                <Eye className="h-4 w-4" />
                                <span>{displayNews.views.toLocaleString()} views</span>
                            </div>

                            {/* Share Button */}
                            <div className="relative ml-auto">
                                <button
                                    onClick={() => setShareMenuOpen(!shareMenuOpen)}
                                    className="flex items-center space-x-2 rounded-lg bg-green-100 px-3 py-1 text-green-700 transition-colors hover:bg-green-200"
                                >
                                    <Share2 className="h-4 w-4" />
                                    <span>Bagikan</span>
                                </button>

                                {shareMenuOpen && (
                                    <div className="absolute right-0 top-full mt-2 w-48 rounded-lg bg-white p-3 shadow-lg ring-1 ring-black ring-opacity-5">
                                        <div className="space-y-2">
                                            <a
                                                href={shareLinks.facebook}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="flex items-center space-x-3 rounded-lg p-2 text-gray-700 transition-colors hover:bg-gray-100"
                                            >
                                                <Facebook className="h-4 w-4 text-blue-600" />
                                                <span>Facebook</span>
                                            </a>
                                            <a
                                                href={shareLinks.twitter}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="flex items-center space-x-3 rounded-lg p-2 text-gray-700 transition-colors hover:bg-gray-100"
                                            >
                                                <Twitter className="h-4 w-4 text-blue-400" />
                                                <span>Twitter</span>
                                            </a>
                                            <a
                                                href={shareLinks.linkedin}
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                className="flex items-center space-x-3 rounded-lg p-2 text-gray-700 transition-colors hover:bg-gray-100"
                                            >
                                                <Linkedin className="h-4 w-4 text-blue-700" />
                                                <span>LinkedIn</span>
                                            </a>
                                            <button
                                                onClick={copyToClipboard}
                                                className="flex w-full items-center space-x-3 rounded-lg p-2 text-gray-700 transition-colors hover:bg-gray-100"
                                            >
                                                <Copy className="h-4 w-4" />
                                                <span>{copySuccess ? 'Tersalin!' : 'Salin Link'}</span>
                                            </button>
                                        </div>
                                    </div>
                                )}
                            </div>
                        </motion.div>

                        {/* Featured Image */}
                        <motion.div
                            className="mb-8 overflow-hidden rounded-xl"
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.6, delay: 0.4 }}
                        >
                            <img src={displayNews.image} alt={displayNews.title} className="h-96 w-full object-cover" />
                        </motion.div>

                        {/* Article Content */}
                        <motion.div
                            className="prose prose-lg prose-headings:text-gray-900 prose-p:text-gray-700 prose-a:text-green-600 prose-strong:text-gray-900 prose-ul:text-gray-700 prose-ol:text-gray-700 prose-blockquote:border-green-500 prose-blockquote:bg-green-50 prose-blockquote:text-gray-700 max-w-none"
                            initial={{ opacity: 0, y: 20 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.6, delay: 0.5 }}
                            dangerouslySetInnerHTML={{ __html: displayNews.content }}
                        />

                        {/* Tags */}
                        {displayNews.tags && displayNews.tags.length > 0 && (
                            <motion.div
                                className="mt-8 border-t border-gray-200 pt-6"
                                initial={{ opacity: 0, y: 20 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={{ duration: 0.6, delay: 0.6 }}
                            >
                                <div className="flex items-center space-x-2">
                                    <Tag className="h-4 w-4 text-gray-500" />
                                    <span className="text-sm font-medium text-gray-700">Tags:</span>
                                </div>
                                <div className="mt-3 flex flex-wrap gap-2">
                                    {displayNews.tags.map((tag) => (
                                        <span key={tag} className="rounded-full bg-gray-100 px-3 py-1 text-sm text-gray-700 hover:bg-gray-200">
                                            #{tag}
                                        </span>
                                    ))}
                                </div>
                            </motion.div>
                        )}
                    </div>
                </div>
            </section>

            {/* Related News */}
            <section className="section-padding bg-gray-50">
                <div className="container-custom">
                    <div className="mx-auto max-w-6xl">
                        <motion.h2
                            className="mb-8 text-2xl font-bold text-gray-900 md:text-3xl"
                            initial={{ opacity: 0, y: 20 }}
                            whileInView={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.6 }}
                            viewport={{ once: true }}
                        >
                            Berita Terkait
                        </motion.h2>

                        <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                            {displayRelatedNews.map((item, index) => (
                                <motion.article
                                    key={item.id}
                                    className="group overflow-hidden rounded-xl bg-white shadow-lg transition-all duration-300 hover:translate-y-[-4px] hover:shadow-xl"
                                    initial={{ opacity: 0, y: 30 }}
                                    whileInView={{ opacity: 1, y: 0 }}
                                    transition={{ duration: 0.6, delay: index * 0.1 }}
                                    viewport={{ once: true }}
                                >
                                    <div className="relative overflow-hidden">
                                        <img
                                            src={item.image}
                                            alt={item.title}
                                            className="h-48 w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                        />
                                        <div className="absolute left-4 top-4 rounded-full bg-green-500 px-3 py-1 text-xs font-medium text-white">
                                            {item.category}
                                        </div>
                                    </div>
                                    <div className="p-6">
                                        <div className="mb-2 text-sm text-gray-500">{formatDate(item.publishedAt)}</div>
                                        <h3 className="mb-3 text-lg font-bold text-gray-900 transition-colors group-hover:text-green-600">
                                            {item.title}
                                        </h3>
                                        <p className="mb-4 text-gray-600">{item.excerpt}</p>
                                        <Button href={`/news/${item.id}`} variant="outline" size="sm" className="group/btn">
                                            Baca Selengkapnya
                                        </Button>
                                    </div>
                                </motion.article>
                            ))}
                        </div>
                    </div>
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
                        <h2 className="mb-6 text-3xl font-bold md:text-4xl">Tertarik Bergabung dengan Kami?</h2>
                        <p className="mb-8 text-xl leading-relaxed text-green-100">
                            Jadilah bagian dari gerakan lingkungan berkelanjutan dan rasakan manfaat mengubah sampah menjadi tabungan.
                        </p>
                        <div className="flex flex-col gap-4 sm:flex-row sm:justify-center">
                            <Button href="/register" size="lg" className="bg-white text-green-600 hover:bg-gray-100">
                                Daftar Sekarang
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
