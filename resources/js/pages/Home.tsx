import { Head } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { ArrowRight, Calendar, Eye, Leaf, PiggyBank, Recycle, Shield, Smartphone, Users } from 'lucide-react';

import Button from '../components/Button';
import ProgramCard from '../components/ProgramCard';
import SectionHeader from '../components/SectionHeader';
import StatCard from '../components/StatCard';
import StepItem from '../components/StepItem';
import TestimonialItem from '../components/TestimonialItem';
import MainLayout from '../layouts/MainLayout';

interface HomeProps {
    stats: {
        members: number;
        recycled_tons: number;
        savings_total: number;
    };
    programs: Array<{
        id: number;
        title: string;
        description: string;
        icon: string;
        color: 'green' | 'blue' | 'purple' | 'yellow';
    }>;
    testimonials: Array<{
        id: number;
        name: string;
        role: string;
        content: string;
        avatar: string;
        rating: number;
    }>;
}

export default function Home({ stats, programs, testimonials }: HomeProps) {
    return (
        <MainLayout>
            <Head>
                <title>e-Bank Sampah Cipta Muri</title>
                <meta name="description" content="e-Bank Sampah Cipta Muri" />
            </Head>

            {/* Hero Section */}
            <section id="home" className="relative flex min-h-screen items-center overflow-hidden">
                {/* Background Image */}
                <div className="absolute inset-0 bg-cover bg-center bg-no-repeat">
                    {/* Overlay */}
                    <div className="absolute inset-0" style={{ backgroundColor: '#90d5f7' }}></div>
                </div>

                {/* Background Pattern */}
                <div className="absolute inset-0 opacity-10">
                    <div className="absolute left-20 top-20 h-72 w-72 animate-pulse rounded-full bg-white mix-blend-multiply blur-xl filter"></div>
                    <div className="animation-delay-2000 absolute right-20 top-40 h-72 w-72 animate-pulse rounded-full bg-yellow-100 mix-blend-multiply blur-xl filter"></div>
                    <div className="animation-delay-4000 absolute bottom-20 left-1/2 h-72 w-72 animate-pulse rounded-full bg-green-100 mix-blend-multiply blur-xl filter"></div>
                </div>

                <div className="container-custom relative z-10">
                    {/* <div className="grid min-h-[80vh] grid-cols-1 items-center gap-12 lg:grid-cols-2">
                        {/* Content */}
                    {/* <motion.div
                            className="text-center lg:text-left"
                            initial={{ opacity: 0, x: -50 }}
                            animate={{ opacity: 1, x: 0 }}
                            transition={{ duration: 0.8 }}
                        >
                            <motion.h1
                                className="mb-6 text-4xl font-bold leading-tight text-white drop-shadow-lg md:text-5xl lg:text-6xl"
                                initial={{ opacity: 0, y: 30 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={{ duration: 0.8, delay: 0.3 }}
                            >
                                Ubah Sampah Jadi <span className="text-yellow-300">Tabungan</span>,{' '}
                                <span className="text-green-300">Wujudkan Lingkungan Bersih</span> Bersama Cipta Muri
                            </motion.h1>

                            <motion.p
                                className="mb-8 max-w-2xl text-xl leading-relaxed text-gray-100 drop-shadow-md"
                                initial={{ opacity: 0, y: 20 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={{ duration: 0.8, delay: 0.4 }}
                            >
                                Bergabunglah dengan revolusi hijau! Dapatkan keuntungan finansial sambil menjaga kelestarian lingkungan melalui sistem
                                bank sampah yang modern dan terpercaya.
                            </motion.p>

                            <motion.div
                                className="flex flex-col justify-center gap-4 sm:flex-row lg:justify-start"
                                initial={{ opacity: 0, y: 20 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={{ duration: 0.8, delay: 0.5 }}
                            >
                                <Button href="/register" size="lg" className="group text-white">
                                    <span className="text-white">Gabung Sekarang</span>
                                    <ArrowRight className="ml-2 h-5 w-5 text-white transition-transform group-hover:translate-x-1" />
                                </Button>
                                <Button
                                    variant="outline"
                                    size="lg"
                                    onClick={() => {
                                        const element = document.querySelector('#tentang');
                                        if (element) {
                                            element.scrollIntoView({ behavior: 'smooth' });
                                        }
                                    }}
                                >
                                    Pelajari Lebih Lanjut
                                </Button>
                            </motion.div>

                            {/* Trust Indicators */}
                    {/* <motion.div
                                className="mt-6 flex flex-wrap items-center justify-center gap-6 border-t border-white/30 pt-8 lg:justify-start"
                                initial={{ opacity: 0 }}
                                animate={{ opacity: 1 }}
                                transition={{ duration: 0.8, delay: 0.6 }}
                            >
                                <div className="flex items-center text-white/90">
                                    <CheckCircle className="mr-2 h-5 w-5 text-green-300" />
                                    <span className="text-sm font-medium">100% Transparan</span>
                                </div>
                                <div className="flex items-center text-white/90">
                                    <Shield className="mr-2 h-5 w-5 text-green-300" />
                                    <span className="text-sm font-medium">Aman & Terpercaya</span>
                                </div>
                                <div className="flex items-center text-white/90">
                                    <Smartphone className="mr-2 h-5 w-5 text-green-300" />
                                    <span className="text-sm font-medium">Berbasis Digital</span>
                                </div>
                            </motion.div>
                        </motion.div>

                        {/* Hero Image */}
                    {/* <motion.div
                            className="relative"
                            initial={{ opacity: 0, x: 50 }}
                            animate={{ opacity: 1, x: 0 }}
                            transition={{ duration: 0.8, delay: 0.2 }}
                        >
                            <img src="/images/AI-2.png" alt="Hero" className="mx-auto w-2/3" />
                        </motion.div>
                    </div> */}
                </div>

                {/* Hero Text Above Mountain */}
                <motion.div
                    className="absolute left-0 right-0 top-32 z-20 text-center text-white"
                    initial={{ opacity: 0, y: 30 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{
                        duration: 1.0,
                        ease: 'easeOut',
                        delay: 1.0,
                    }}
                >
                    <motion.p
                        className="mb-2 text-sm font-medium uppercase tracking-widest text-white/80"
                        initial={{ opacity: 0, y: 20 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.8, delay: 1.2 }}
                    >
                        Portal Resmi
                    </motion.p>
                    <motion.h1
                        className="mb-2 text-4xl font-bold drop-shadow-lg md:text-5xl lg:text-6xl"
                        initial={{ opacity: 0, y: 20 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.8, delay: 1.4 }}
                    >
                        e-Bank Sampah Cipta Muri
                    </motion.h1>
                    <motion.p
                        className="text-lg font-medium text-white/90 drop-shadow-md md:text-xl"
                        initial={{ opacity: 0, y: 30 }}
                        animate={{ opacity: 1, y: 10 }}
                        transition={{ duration: 0.8, delay: 1.6 }}
                    >
                        Desa Muntang
                    </motion.p>
                </motion.div>

                {/* Animated Clouds */}
                <div className="z-5 absolute inset-0">
                    {/* Left Cloud */}
                    <motion.img
                        src="/images/Hero/awan.png"
                        alt="Awan Kiri"
                        className="absolute left-2 top-1/4 h-16 w-24 sm:left-5 sm:h-20 sm:w-32 md:left-10 md:h-32 md:w-48"
                        animate={{
                            x: [-50, 50, -50],
                            y: [-10, 10, -10],
                            opacity: [0.7, 0.9, 0.7],
                        }}
                        transition={{
                            duration: 8,
                            repeat: Infinity,
                            ease: 'easeInOut',
                        }}
                        initial={{ opacity: 0, x: -50, y: -10 }}
                    />

                    {/* Right Cloud */}
                    <motion.img
                        src="/images/Hero/awan.png"
                        alt="Awan Kanan"
                        className="absolute right-2 top-1/4 h-16 w-24 sm:right-5 sm:h-20 sm:w-32 md:right-10 md:h-32 md:w-48"
                        animate={{
                            x: [50, -30, 50],
                            y: [10, -5, 10],
                            opacity: [0.6, 0.8, 0.6],
                        }}
                        transition={{
                            duration: 10,
                            repeat: Infinity,
                            ease: 'easeInOut',
                            delay: 2,
                        }}
                        initial={{ opacity: 0, x: 50, y: 10 }}
                    />

                    {/* Additional Left Cloud (smaller) */}
                    <motion.img
                        src="/images/Hero/awan.png"
                        alt="Awan Kiri Kecil"
                        className="absolute left-20 top-1/3 h-12 w-20 sm:left-32 sm:h-16 sm:w-24 md:left-60 md:h-20 md:w-32"
                        animate={{
                            x: [-30, 40, -30],
                            y: [5, -8, 5],
                            opacity: [0.4, 0.6, 0.4],
                        }}
                        transition={{
                            duration: 12,
                            repeat: Infinity,
                            ease: 'easeInOut',
                            delay: 4,
                        }}
                        initial={{ opacity: 0, x: -30, y: 5 }}
                    />

                    {/* Additional Right Cloud (smaller) */}
                    <motion.img
                        src="/images/Hero/awan.png"
                        alt="Awan Kanan Kecil"
                        className="w-22 sm:h-18 absolute right-20 top-1/3 h-14 sm:right-32 sm:w-28 md:right-60 md:h-24 md:w-36"
                        animate={{
                            x: [40, -25, 40],
                            y: [5, 8, 5],
                            opacity: [0.5, 0.7, 0.5],
                        }}
                        transition={{
                            duration: 9,
                            repeat: Infinity,
                            ease: 'easeInOut',
                            delay: 1,
                        }}
                        initial={{ opacity: 0, x: 40, y: 5 }}
                    />
                </div>

                {/* Animated Birds */}
                <div className="z-15 absolute inset-0">
                    {/* Flying Bird 1 */}
                    <motion.img
                        src="/images/Hero/burung.png"
                        alt="Burung Terbang"
                        className="sm:w-18 absolute left-1/4 top-1/2 h-8 w-12 sm:h-12 md:h-16 md:w-24"
                        animate={{
                            x: [-100, 100, 200, -100],
                            y: [0, -20, -10, 0],
                            rotate: [0, 5, -5, 0],
                        }}
                        transition={{
                            duration: 15,
                            repeat: Infinity,
                            ease: 'linear',
                            delay: 0,
                        }}
                        initial={{ opacity: 0, x: -100 }}
                        whileInView={{ opacity: 1 }}
                    />

                    {/* Flying Bird 2 */}
                    <motion.img
                        src="/images/Hero/burung.png"
                        alt="Burung Terbang 2"
                        className="md:w-18 absolute right-1/4 top-2/3 h-6 w-9 sm:h-9 sm:w-14 md:h-12"
                        animate={{
                            x: [150, -50, -150, 150],
                            y: [-15, 5, -5, -15],
                            rotate: [0, -3, 3, 0],
                        }}
                        transition={{
                            duration: 18,
                            repeat: Infinity,
                            ease: 'linear',
                            delay: 5,
                        }}
                        initial={{ opacity: 0, x: 150 }}
                        whileInView={{ opacity: 0.8 }}
                    />

                    {/* Flying Bird 3 (smaller, background) */}
                    <motion.img
                        src="/images/Hero/burung.png"
                        alt="Burung Terbang 3"
                        className="absolute left-1/3 top-1/2 h-4 w-8 opacity-60 sm:h-6 sm:w-12 md:h-10 md:w-16"
                        animate={{
                            x: [-80, 80, 160, -80],
                            y: [10, -10, 5, 10],
                            rotate: [0, 2, -2, 0],
                        }}
                        transition={{
                            duration: 20,
                            repeat: Infinity,
                            ease: 'linear',
                            delay: 10,
                        }}
                        initial={{ opacity: 0, x: -80 }}
                        whileInView={{ opacity: 0.6 }}
                    />
                </div>

                {/* Mountain Image with Animation */}
                <motion.div
                    className="absolute bottom-0 left-0 right-0 z-10 flex w-full justify-center"
                    initial={{ opacity: 1.23, y: 180, x: 40 }}
                    animate={{ opacity: 1, y: 80, x: 40 }}
                    transition={{
                        duration: 2.0,
                        ease: 'easeOut',
                        delay: 0.5,
                    }}
                >
                    <motion.img
                        src="/images/Hero/gunung.png"
                        alt="Gunung"
                        className="h-auto w-full max-w-4xl object-contain"
                        initial={{ scale: 1.1, opacity: 0 }}
                        animate={{ scale: 1.1, opacity: 1 }}
                        transition={{
                            duration: 0.8,
                            ease: 'easeOut',
                            delay: 0.5,
                        }}
                        whileHover={{
                            scale: 1.15,
                            transition: { duration: 0.3 },
                        }}
                    />
                </motion.div>

                {/* footer Image with Animation */}
                <motion.div
                    className="absolute bottom-0 left-0 right-0 z-10 w-full"
                    initial={{ opacity: 0, y: 100 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{
                        duration: 1.2,
                        ease: 'easeOut',
                        delay: 0.3,
                    }}
                >
                    <motion.img
                        src="/images/Hero/footer-hero.png"
                        alt="footer"
                        className="h-auto w-full object-cover"
                        initial={{ scale: 1.1, opacity: 0 }}
                        animate={{ scale: 1, opacity: 1 }}
                        transition={{
                            duration: 1.0,
                            ease: 'easeOut',
                            delay: 0.5,
                        }}
                    />
                </motion.div>
            </section>

            {/* Stats Section */}
            <section className="section-padding relative bg-white">
                <div className="container-custom">
                    <div className="grid grid-cols-1 gap-8 md:grid-cols-3">
                        <StatCard value={stats.members} label="Anggota Aktif" suffix="+" icon={<Users />} delay={0.1} />
                        <StatCard value={stats.recycled_tons} label="Ton Sampah Didaur Ulang" suffix=" Ton" icon={<Recycle />} delay={0.2} />
                        <StatCard value={stats.savings_total} label="Total Tabungan" prefix="Rp " icon={<PiggyBank />} delay={0.3} />
                    </div>
                </div>
            </section>

            {/* About Section */}
            <section id="tentang" className="section-padding bg-gray-50">
                <div className="container-custom">
                    {/* Main About Content */}
                    <div className="grid grid-cols-1 items-center gap-12 lg:grid-cols-2">
                        {/* Left Content */}
                        <motion.div
                            initial={{ opacity: 0, x: -50 }}
                            whileInView={{ opacity: 1, x: 0 }}
                            transition={{ duration: 0.8 }}
                            viewport={{ once: true }}
                        >
                            <motion.p
                                className="mb-4 text-sm font-medium uppercase tracking-widest text-green-600"
                                initial={{ opacity: 0, y: 20 }}
                                whileInView={{ opacity: 1, y: 0 }}
                                transition={{ duration: 0.6, delay: 0.2 }}
                                viewport={{ once: true }}
                            >
                                Tentang Kami
                            </motion.p>

                            <motion.h2
                                className="mb-6 text-3xl font-bold text-gray-900 md:text-4xl"
                                initial={{ opacity: 0, y: 20 }}
                                whileInView={{ opacity: 1, y: 0 }}
                                transition={{ duration: 0.6, delay: 0.3 }}
                                viewport={{ once: true }}
                            >
                                Bank Sampah Cipta Muri Desa Muntang
                            </motion.h2>

                            <motion.p
                                className="mb-6 text-lg leading-relaxed text-gray-600"
                                initial={{ opacity: 0, y: 20 }}
                                whileInView={{ opacity: 1, y: 0 }}
                                transition={{ duration: 0.6, delay: 0.4 }}
                                viewport={{ once: true }}
                            >
                                Bank Sampah Cipta Muri adalah pionir pengelolaan sampah berkelanjutan di Desa Muntang yang telah berdiri sejak tahun
                                2018. Kami hadir untuk mengubah paradigma masyarakat tentang sampah dari yang semula dianggap sebagai limbah menjadi
                                aset berharga yang dapat memberikan nilai ekonomi dan lingkungan.
                            </motion.p>

                            <motion.p
                                className="mb-6 text-lg leading-relaxed text-gray-600"
                                initial={{ opacity: 0, y: 20 }}
                                whileInView={{ opacity: 1, y: 0 }}
                                transition={{ duration: 0.6, delay: 0.5 }}
                                viewport={{ once: true }}
                            >
                                Dengan mengusung konsep ekonomi sirkular, kami mengintegrasikan teknologi digital terdepan dalam sistem pengelolaan
                                sampah yang transparan dan akuntabel. Setiap nasabah dapat memantau perkembangan tabungan mereka secara real-time
                                melalui platform digital yang user-friendly.
                            </motion.p>

                            <motion.p
                                className="mb-8 text-lg leading-relaxed text-gray-600"
                                initial={{ opacity: 0, y: 20 }}
                                whileInView={{ opacity: 1, y: 0 }}
                                transition={{ duration: 0.6, delay: 0.6 }}
                                viewport={{ once: true }}
                            >
                                Lebih dari sekadar pengelolaan sampah, kami berkomitmen untuk menciptakan dampak sosial yang berkelanjutan melalui
                                program edukasi lingkungan, pemberdayaan UMKM lokal, dan pengembangan komunitas peduli lingkungan. Bersama-sama, kita
                                wujudkan visi desa bersih, sehat, dan sejahtera untuk generasi mendatang.
                            </motion.p>

                            {/* Key Features */}
                            <motion.div
                                className="grid grid-cols-2 gap-4"
                                initial={{ opacity: 0, y: 20 }}
                                whileInView={{ opacity: 1, y: 0 }}
                                transition={{ duration: 0.6, delay: 0.7 }}
                                viewport={{ once: true }}
                            >
                                <div className="flex items-center space-x-3">
                                    <div className="flex h-10 w-10 items-center justify-center rounded-full bg-green-100">
                                        <Shield className="h-5 w-5 text-green-600" />
                                    </div>
                                    <span className="font-medium text-gray-900">100% Transparan</span>
                                </div>
                                <div className="flex items-center space-x-3">
                                    <div className="flex h-10 w-10 items-center justify-center rounded-full bg-green-100">
                                        <Leaf className="h-5 w-5 text-green-600" />
                                    </div>
                                    <span className="font-medium text-gray-900">Ramah Lingkungan</span>
                                </div>
                                <div className="flex items-center space-x-3">
                                    <div className="flex h-10 w-10 items-center justify-center rounded-full bg-green-100">
                                        <Smartphone className="h-5 w-5 text-green-600" />
                                    </div>
                                    <span className="font-medium text-gray-900">Berbasis Digital</span>
                                </div>
                                <div className="flex items-center space-x-3">
                                    <div className="flex h-10 w-10 items-center justify-center rounded-full bg-green-100">
                                        <Users className="h-5 w-5 text-green-600" />
                                    </div>
                                    <span className="font-medium text-gray-900">Komunitas Kuat</span>
                                </div>
                            </motion.div>
                        </motion.div>

                        {/* Right Logo */}
                        <motion.div
                            className="flex justify-center lg:justify-end"
                            initial={{ opacity: 0, x: 50 }}
                            whileInView={{ opacity: 1, x: 0 }}
                            transition={{ duration: 0.8, delay: 0.2 }}
                            viewport={{ once: true }}
                        >
                            <motion.div className="relative" whileHover={{ scale: 1.05 }} transition={{ duration: 0.3 }}>
                                {/* Background Circle */}
                                <div className="absolute inset-0 rounded-full bg-gradient-to-br from-green-100 to-green-200 opacity-20"></div>

                                {/* Logo */}
                                <img
                                    src="/logo.png"
                                    alt="Logo Bank Sampah Cipta Muri"
                                    className="relative z-10 h-80 w-80 rounded-full bg-white object-contain p-8 shadow-2xl"
                                />

                                {/* Decorative Elements */}
                                <div className="absolute -right-4 -top-4 h-20 w-20 rounded-full bg-green-200 opacity-60"></div>
                                <div className="absolute -bottom-6 -left-6 h-16 w-16 rounded-full bg-blue-200 opacity-40"></div>
                            </motion.div>
                        </motion.div>
                    </div>

                    {/* Three Pillars Section */}
                    <div className="mt-20">
                        <motion.div
                            className="text-center"
                            initial={{ opacity: 0, y: 30 }}
                            whileInView={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.6 }}
                            viewport={{ once: true }}
                        >
                            <h3 className="mb-4 text-2xl font-bold text-gray-900 md:text-3xl">3 Pilar Utama Kami</h3>
                            <p className="mx-auto mb-12 max-w-2xl text-gray-600">
                                Komitmen kami terhadap keberlanjutan lingkungan dibangun atas tiga pilar fundamental yang menjadi landasan setiap
                                aktivitas dan inovasi yang kami lakukan.
                            </p>
                        </motion.div>

                        <div className="grid grid-cols-1 gap-8 md:grid-cols-3">
                            {/* Transparan */}
                            <motion.div
                                className="group text-center"
                                initial={{ opacity: 0, y: 30 }}
                                whileInView={{ opacity: 1, y: 0 }}
                                transition={{ duration: 0.6, delay: 0.1 }}
                                viewport={{ once: true }}
                            >
                                <motion.div
                                    className="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-green-100 transition-colors duration-300 group-hover:bg-green-200"
                                    whileHover={{ scale: 1.1 }}
                                >
                                    <Shield className="h-10 w-10 text-green-600" />
                                </motion.div>
                                <h4 className="mb-4 text-xl font-bold text-gray-900">Transparan & Akuntabel</h4>
                                <p className="leading-relaxed text-gray-600">
                                    Sistem tracking digital yang terintegrasi memungkinkan setiap nasabah memantau transaksi, perkembangan tabungan,
                                    dan dampak lingkungan secara real-time. Laporan keuangan dan operasional tersedia untuk umum sebagai bentuk
                                    akuntabilitas publik.
                                </p>
                            </motion.div>

                            {/* Ramah Lingkungan */}
                            <motion.div
                                className="group text-center"
                                initial={{ opacity: 0, y: 30 }}
                                whileInView={{ opacity: 1, y: 0 }}
                                transition={{ duration: 0.6, delay: 0.2 }}
                                viewport={{ once: true }}
                            >
                                <motion.div
                                    className="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-green-100 transition-colors duration-300 group-hover:bg-green-200"
                                    whileHover={{ scale: 1.1 }}
                                >
                                    <Leaf className="h-10 w-10 text-green-600" />
                                </motion.div>
                                <h4 className="mb-4 text-xl font-bold text-gray-900">Berkelanjutan & Hijau</h4>
                                <p className="leading-relaxed text-gray-600">
                                    Menerapkan prinsip ekonomi sirkular dalam setiap aspek operasional. Dari proses daur ulang yang optimal hingga
                                    edukasi masyarakat tentang gaya hidup zero waste. Setiap kegiatan dirancang untuk meminimalkan jejak karbon dan
                                    memaksimalkan manfaat ekologi.
                                </p>
                            </motion.div>

                            {/* Berbasis Digital */}
                            <motion.div
                                className="group text-center"
                                initial={{ opacity: 0, y: 30 }}
                                whileInView={{ opacity: 1, y: 0 }}
                                transition={{ duration: 0.6, delay: 0.3 }}
                                viewport={{ once: true }}
                            >
                                <motion.div
                                    className="mx-auto mb-6 flex h-20 w-20 items-center justify-center rounded-full bg-green-100 transition-colors duration-300 group-hover:bg-green-200"
                                    whileHover={{ scale: 1.1 }}
                                >
                                    <Smartphone className="h-10 w-10 text-green-600" />
                                </motion.div>
                                <h4 className="mb-4 text-xl font-bold text-gray-900">Inovatif & Modern</h4>
                                <p className="leading-relaxed text-gray-600">
                                    Platform digital yang user-friendly dengan teknologi blockchain untuk keamanan data, sistem AI untuk optimalisasi
                                    operasional, dan aplikasi mobile yang memudahkan nasabah dalam bertransaksi. Inovasi teknologi untuk keberlanjutan
                                    masa depan.
                                </p>
                            </motion.div>
                        </div>
                    </div>
                </div>
            </section>

            {/* How It Works Section */}
            <section id="cara-kerja" className="section-padding bg-white">
                <div className="container-custom">
                    <SectionHeader
                        subtitle="Cara Kerja"
                        title="4 Langkah Mudah Mengubah Sampah Jadi Tabungan"
                        description="Proses yang sederhana dan efektif untuk memulai perjalanan Anda menuju kehidupan yang lebih berkelanjutan."
                    />

                    <div className="mx-auto mt-16 max-w-4xl">
                        <div className="space-y-12">
                            <StepItem
                                number="01"
                                title="Kumpulkan"
                                description="Pisahkan sampah organik dan anorganik di rumah Anda. Pastikan sampah dalam kondisi bersih dan kering untuk mendapatkan nilai terbaik."
                                icon="Trash2"
                                delay={0.1}
                            />
                            <StepItem
                                number="02"
                                title="Setor"
                                description="Bawa sampah yang sudah dipilah ke lokasi Bank Sampah Cipta Muri terdekat atau jadwalkan pickup melalui aplikasi mobile kami."
                                icon="TrendingUp"
                                delay={0.2}
                            />
                            <StepItem
                                number="03"
                                title="Dapat Poin"
                                description="Sistem konversi otomatis akan menghitung nilai sampah dan langsung menambahkan poin ke akun tabungan digital Anda."
                                icon="HandCoins"
                                delay={0.3}
                            />
                            <StepItem
                                number="04"
                                title="Tukar"
                                description="Tukarkan poin Anda dengan uang tunai, transfer bank, atau berbagai produk menarik dari mitra UMKM kami."
                                icon="RefreshCw"
                                delay={0.4}
                                isLast={true}
                            />
                        </div>
                    </div>
                </div>
            </section>

            {/* Programs Section */}
            <section id="program" className="section-padding bg-gradient-to-br from-green-50 to-blue-50">
                <div className="container-custom">
                    <SectionHeader
                        subtitle="Program Kami"
                        title="4 Program Unggulan untuk Masa Depan Berkelanjutan"
                        description="Beragam program inovatif yang dirancang untuk mendukung gaya hidup ramah lingkungan dan pemberdayaan komunitas."
                    />

                    <div className="mt-16 grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
                        {programs.map((program, index) => (
                            <ProgramCard
                                key={program.id}
                                title={program.title}
                                description={program.description}
                                icon={program.icon}
                                color={program.color}
                                delay={index * 0.1}
                            />
                        ))}
                    </div>
                </div>
            </section>

            {/* Testimonials Section */}
            <section className="section-padding bg-white">
                <div className="container-custom">
                    <SectionHeader
                        subtitle="Testimoni"
                        title="Apa Kata Anggota Kami"
                        description="Mendengar langsung dari komunitas Bank Sampah Cipta Muri tentang pengalaman dan manfaat yang mereka rasakan."
                    />

                    <div className="mt-16 grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                        {testimonials.map((testimonial, index) => (
                            <TestimonialItem
                                key={testimonial.id}
                                name={testimonial.name}
                                role={testimonial.role}
                                content={testimonial.content}
                                avatar={testimonial.avatar}
                                rating={testimonial.rating}
                                delay={index * 0.1}
                            />
                        ))}
                    </div>
                </div>
            </section>

            {/* News & Publications Section */}
            <section className="section-padding bg-gray-50">
                <div className="container-custom">
                    <SectionHeader
                        subtitle="Berita & Publikasi"
                        title="Berita Terkini & Aktivitas Kami"
                        description="Ikuti perkembangan terbaru Bank Sampah Cipta Muri, prestasi yang diraih, dan berbagai kegiatan yang telah dilaksanakan."
                    />

                    <div className="mt-16 grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                        {/* News Item 1 */}
                        <motion.article
                            className="group overflow-hidden rounded-xl bg-white shadow-lg transition-all duration-300 hover:translate-y-[-4px] hover:shadow-xl"
                            initial={{ opacity: 0, y: 30 }}
                            whileInView={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.6, delay: 0.1 }}
                            viewport={{ once: true }}
                        >
                            <div className="relative overflow-hidden">
                                <img
                                    src="/images/news/news1.jpg"
                                    alt="Penghargaan Bank Sampah Terbaik"
                                    className="h-48 w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                />
                                <div className="absolute left-4 top-4 rounded-full bg-green-500 px-3 py-1 text-xs font-medium text-white">
                                    Penghargaan
                                </div>
                            </div>
                            <div className="p-6">
                                <div className="mb-3 flex items-center space-x-4 text-sm text-gray-500">
                                    <div className="flex items-center space-x-1">
                                        <Calendar className="h-4 w-4" />
                                        <span>15 Agustus 2024</span>
                                    </div>
                                    <div className="flex items-center space-x-1">
                                        <Eye className="h-4 w-4" />
                                        <span>1,234 views</span>
                                    </div>
                                </div>
                                <h3 className="mb-3 text-lg font-bold text-gray-900 transition-colors group-hover:text-green-600">
                                    Bank Sampah Cipta Muri Raih Penghargaan Bank Sampah Terbaik Jawa Tengah 2024
                                </h3>
                                <p className="mb-4 leading-relaxed text-gray-600">
                                    Prestasi membanggakan diraih Bank Sampah Cipta Muri sebagai Bank Sampah Terbaik tingkat Provinsi Jawa Tengah
                                    berkat inovasi sistem digital dan dampak positif terhadap lingkungan.
                                </p>
                                <Button variant="outline" size="sm" className="group/btn w-full" href="/news/penghargaan-bank-sampah-terbaik-2024">
                                    Baca Selengkapnya
                                    <ArrowRight className="ml-2 h-4 w-4 transition-transform group-hover/btn:translate-x-1" />
                                </Button>
                            </div>
                        </motion.article>

                        {/* News Item 2 */}
                        <motion.article
                            className="group overflow-hidden rounded-xl bg-white shadow-lg transition-all duration-300 hover:translate-y-[-4px] hover:shadow-xl"
                            initial={{ opacity: 0, y: 30 }}
                            whileInView={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.6, delay: 0.2 }}
                            viewport={{ once: true }}
                        >
                            <div className="relative overflow-hidden">
                                <img
                                    src="/images/news/news2.jpg"
                                    alt="Workshop Edukasi Lingkungan"
                                    className="h-48 w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                />
                                <div className="absolute left-4 top-4 rounded-full bg-blue-500 px-3 py-1 text-xs font-medium text-white">
                                    Kegiatan
                                </div>
                            </div>
                            <div className="p-6">
                                <div className="mb-3 flex items-center space-x-4 text-sm text-gray-500">
                                    <div className="flex items-center space-x-1">
                                        <Calendar className="h-4 w-4" />
                                        <span>28 Juli 2024</span>
                                    </div>
                                    <div className="flex items-center space-x-1">
                                        <Eye className="h-4 w-4" />
                                        <span>856 views</span>
                                    </div>
                                </div>
                                <h3 className="mb-3 text-lg font-bold text-gray-900 transition-colors group-hover:text-green-600">
                                    Workshop Edukasi Lingkungan untuk 500 Siswa SD di Kabupaten Cilacap
                                </h3>
                                <p className="mb-4 leading-relaxed text-gray-600">
                                    Program edukasi lingkungan yang diselenggarakan Bank Sampah Cipta Muri berhasil menjangkau 500 siswa SD dengan
                                    materi pengelolaan sampah dan gaya hidup berkelanjutan.
                                </p>
                                <Button variant="outline" size="sm" className="group/btn w-full" href="/news/workshop-edukasi-lingkungan-siswa-sd">
                                    Baca Selengkapnya
                                    <ArrowRight className="ml-2 h-4 w-4 transition-transform group-hover/btn:translate-x-1" />
                                </Button>
                            </div>
                        </motion.article>

                        {/* News Item 3 */}
                        <motion.article
                            className="group overflow-hidden rounded-xl bg-white shadow-lg transition-all duration-300 hover:translate-y-[-4px] hover:shadow-xl"
                            initial={{ opacity: 0, y: 30 }}
                            whileInView={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.6, delay: 0.3 }}
                            viewport={{ once: true }}
                        >
                            <div className="relative overflow-hidden">
                                <img
                                    src="/images/news/news3.jpg"
                                    alt="Peluncuran Aplikasi Mobile"
                                    className="h-48 w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                />
                                <div className="absolute left-4 top-4 rounded-full bg-purple-500 px-3 py-1 text-xs font-medium text-white">
                                    Inovasi
                                </div>
                            </div>
                            <div className="p-6">
                                <div className="mb-3 flex items-center space-x-4 text-sm text-gray-500">
                                    <div className="flex items-center space-x-1">
                                        <Calendar className="h-4 w-4" />
                                        <span>10 Juni 2024</span>
                                    </div>
                                    <div className="flex items-center space-x-1">
                                        <Eye className="h-4 w-4" />
                                        <span>2,145 views</span>
                                    </div>
                                </div>
                                <h3 className="mb-3 text-lg font-bold text-gray-900 transition-colors group-hover:text-green-600">
                                    Peluncuran Aplikasi Mobile "EcoBank Cipta Muri" dengan Fitur AI dan Blockchain
                                </h3>
                                <p className="mb-4 leading-relaxed text-gray-600">
                                    Inovasi terdepan dalam pengelolaan sampah digital dengan meluncurkan aplikasi mobile yang dilengkapi teknologi AI
                                    untuk identifikasi jenis sampah dan blockchain untuk transparansi transaksi.
                                </p>
                                <Button variant="outline" size="sm" className="group/btn w-full" href="/news/peluncuran-aplikasi-mobile-ecobank">
                                    Baca Selengkapnya
                                    <ArrowRight className="ml-2 h-4 w-4 transition-transform group-hover/btn:translate-x-1" />
                                </Button>
                            </div>
                        </motion.article>

                        {/* News Item 4 */}
                        <motion.article
                            className="group overflow-hidden rounded-xl bg-white shadow-lg transition-all duration-300 hover:translate-y-[-4px] hover:shadow-xl"
                            initial={{ opacity: 0, y: 30 }}
                            whileInView={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.6, delay: 0.4 }}
                            viewport={{ once: true }}
                        >
                            <div className="relative overflow-hidden">
                                <img
                                    src="/images/news/news4.jpg"
                                    alt="Kemitraan UMKM Lokal"
                                    className="h-48 w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                />
                                <div className="absolute left-4 top-4 rounded-full bg-yellow-500 px-3 py-1 text-xs font-medium text-white">
                                    Kemitraan
                                </div>
                            </div>
                            <div className="p-6">
                                <div className="mb-3 flex items-center space-x-4 text-sm text-gray-500">
                                    <div className="flex items-center space-x-1">
                                        <Calendar className="h-4 w-4" />
                                        <span>22 Mei 2024</span>
                                    </div>
                                    <div className="flex items-center space-x-1">
                                        <Eye className="h-4 w-4" />
                                        <span>678 views</span>
                                    </div>
                                </div>
                                <h3 className="mb-3 text-lg font-bold text-gray-900 transition-colors group-hover:text-green-600">
                                    Kemitraan dengan 50 UMKM Lokal untuk Program Tukar Poin Produk Ramah Lingkungan
                                </h3>
                                <p className="mb-4 leading-relaxed text-gray-600">
                                    Ekspansi jaringan kemitraan dengan 50 UMKM lokal memungkinkan nasabah menukarkan poin tabungan dengan berbagai
                                    produk ramah lingkungan dan mendukung ekonomi lokal.
                                </p>
                                <Button variant="outline" size="sm" className="group/btn w-full" href="/news/kemitraan-umkm-lokal-program-tukar-poin">
                                    Baca Selengkapnya
                                    <ArrowRight className="ml-2 h-4 w-4 transition-transform group-hover/btn:translate-x-1" />
                                </Button>
                            </div>
                        </motion.article>

                        {/* News Item 5 */}
                        <motion.article
                            className="group overflow-hidden rounded-xl bg-white shadow-lg transition-all duration-300 hover:translate-y-[-4px] hover:shadow-xl"
                            initial={{ opacity: 0, y: 30 }}
                            whileInView={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.6, delay: 0.5 }}
                            viewport={{ once: true }}
                        >
                            <div className="relative overflow-hidden">
                                <img
                                    src="/images/news/news5.jpg"
                                    alt="Pelatihan Pengelolaan Sampah"
                                    className="h-48 w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                />
                                <div className="absolute left-4 top-4 rounded-full bg-indigo-500 px-3 py-1 text-xs font-medium text-white">
                                    Pelatihan
                                </div>
                            </div>
                            <div className="p-6">
                                <div className="mb-3 flex items-center space-x-4 text-sm text-gray-500">
                                    <div className="flex items-center space-x-1">
                                        <Calendar className="h-4 w-4" />
                                        <span>05 April 2024</span>
                                    </div>
                                    <div className="flex items-center space-x-1">
                                        <Eye className="h-4 w-4" />
                                        <span>1,89 views</span>
                                    </div>
                                </div>
                                <h3 className="mb-3 text-lg font-bold text-gray-900 transition-colors group-hover:text-green-600">
                                    Pelatihan Pengelolaan Sampah untuk 20 Desa se-Kabupaten Cilacap
                                </h3>
                                <p className="mb-4 leading-relaxed text-gray-600">
                                    Program transfer knowledge kepada 20 desa di Kabupaten Cilacap untuk mendirikan bank sampah mandiri dengan sistem
                                    pengelolaan yang sustainable dan profitable.
                                </p>
                                <Button variant="outline" size="sm" className="group/btn w-full" href="/news/pelatihan-pengelolaan-sampah-20-desa">
                                    Baca Selengkapnya
                                    <ArrowRight className="ml-2 h-4 w-4 transition-transform group-hover/btn:translate-x-1" />
                                </Button>
                            </div>
                        </motion.article>

                        {/* News Item 6 */}
                        <motion.article
                            className="group overflow-hidden rounded-xl bg-white shadow-lg transition-all duration-300 hover:translate-y-[-4px] hover:shadow-xl"
                            initial={{ opacity: 0, y: 30 }}
                            whileInView={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.6, delay: 0.6 }}
                            viewport={{ once: true }}
                        >
                            <div className="relative overflow-hidden">
                                <img
                                    src="/images/news/news6.jpg"
                                    alt="Milestone 1000 Anggota"
                                    className="h-48 w-full object-cover transition-transform duration-300 group-hover:scale-105"
                                />
                                <div className="absolute left-4 top-4 rounded-full bg-red-500 px-3 py-1 text-xs font-medium text-white">
                                    Milestone
                                </div>
                            </div>
                            <div className="p-6">
                                <div className="mb-3 flex items-center space-x-4 text-sm text-gray-500">
                                    <div className="flex items-center space-x-1">
                                        <Calendar className="h-4 w-4" />
                                        <span>18 Maret 2024</span>
                                    </div>
                                    <div className="flex items-center space-x-1">
                                        <Eye className="h-4 w-4" />
                                        <span>3,256 views</span>
                                    </div>
                                </div>
                                <h3 className="mb-3 text-lg font-bold text-gray-900 transition-colors group-hover:text-green-600">
                                    Milestone 1.250 Anggota Aktif dan Total Tabungan Rp 45 Miliar
                                </h3>
                                <p className="mb-4 leading-relaxed text-gray-600">
                                    Pencapaian luar biasa dengan 1.250 anggota aktif dan total tabungan mencapai Rp 45 miliar, membuktikan kepercayaan
                                    masyarakat terhadap sistem bank sampah digital.
                                </p>
                                <Button
                                    variant="outline"
                                    size="sm"
                                    className="group/btn w-full"
                                    href="/news/milestone-1250-anggota-45-miliar-tabungan"
                                >
                                    Baca Selengkapnya
                                    <ArrowRight className="ml-2 h-4 w-4 transition-transform group-hover/btn:translate-x-1" />
                                </Button>
                            </div>
                        </motion.article>
                    </div>

                    {/* View All News Button */}
                    <motion.div
                        className="mt-12 text-center"
                        initial={{ opacity: 0, y: 20 }}
                        whileInView={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.6, delay: 0.7 }}
                        viewport={{ once: true }}
                    >
                        <Button href="/news" size="lg" className="group bg-green-600 hover:bg-green-700">
                            Lihat Semua Berita
                            <ArrowRight className="ml-2 h-5 w-5 transition-transform group-hover:translate-x-1" />
                        </Button>
                    </motion.div>
                </div>
            </section>

            {/* Location Section */}
            <section id="lokasi" className="section-padding bg-gray-50">
                <div className="container-custom">
                    <SectionHeader
                        title="Lokasi Bank Sampah"
                        subtitle="Kunjungi langsung kantor Bank Sampah untuk konsultasi dan pelayanan terbaik"
                        centered
                    />

                    <motion.div
                        className="mx-auto mt-16 max-w-4xl overflow-hidden rounded-xl shadow-lg"
                        initial={{ opacity: 0, y: 30 }}
                        whileInView={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.8 }}
                        viewport={{ once: true }}
                    >
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d940.0992958394846!2d109.36255426954996!3d-7.449328999535118!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zN8KwMjYnNTcuNiJTIDEwOcKwMjEnNDcuNSJF!5e1!3m2!1sen!2sid!4v1755706259681!5m2!1sen!2sid"
                            width="100%"
                            height="450"
                            style={{ border: 0 }}
                            allowFullScreen
                            loading="lazy"
                            referrerPolicy="no-referrer-when-downgrade"
                            className="w-full"
                        />
                    </motion.div>

                    <motion.div
                        className="mx-auto mt-8 max-w-2xl text-center"
                        initial={{ opacity: 0, y: 20 }}
                        whileInView={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.8, delay: 0.3 }}
                        viewport={{ once: true }}
                    >
                        <div className="rounded-lg bg-white p-6 shadow-md">
                            <h3 className="mb-4 text-xl font-semibold text-gray-800">Bank Sampah "Sampah Sahabatku"</h3>
                            <p className="text-gray-600">Jl. Raya Cilacap, Jawa Tengah, Indonesia</p>
                            <div className="mt-4 flex justify-center gap-6 text-sm text-gray-500">
                                <span>📞 (021) 1234-5678</span>
                                <span>📱 +62 812-3456-7890</span>
                                <span>📧 info@ciptamuri.co.id</span>
                            </div>
                        </div>
                    </motion.div>
                </div>
            </section>

            {/* CTA Section */}
            <section id="kontak" className="section-padding relative overflow-hidden bg-gradient-to-r from-green-600 to-green-700 text-white">
                {/* Background Pattern */}
                <div className="absolute inset-0 opacity-10">
                    <div className="absolute left-0 top-0 h-72 w-72 rounded-full bg-white mix-blend-multiply blur-xl filter"></div>
                    <div className="absolute bottom-0 right-0 h-72 w-72 rounded-full bg-yellow-300 mix-blend-multiply blur-xl filter"></div>
                </div>

                <div className="container-custom relative z-10">
                    <div className="mx-auto max-w-4xl text-center">
                        <motion.h2
                            className="mb-6 text-4xl font-bold md:text-5xl"
                            initial={{ opacity: 0, y: 30 }}
                            whileInView={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.8 }}
                            viewport={{ once: true }}
                        >
                            Siap Memulai Perjalanan Hijau Anda?
                        </motion.h2>

                        <motion.p
                            className="mb-8 text-xl leading-relaxed opacity-90"
                            initial={{ opacity: 0, y: 20 }}
                            whileInView={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.8, delay: 0.2 }}
                            viewport={{ once: true }}
                        >
                            Bergabunglah dengan ribuan anggota lainnya yang telah merasakan manfaat mengubah sampah menjadi tabungan. Daftar sekarang
                            dan dapatkan bonus poin welcome sebesar Rp 50.000!
                        </motion.p>

                        <motion.div
                            className="flex flex-col items-center justify-center gap-6 sm:flex-row"
                            initial={{ opacity: 0, y: 20 }}
                            whileInView={{ opacity: 1, y: 0 }}
                            transition={{ duration: 0.8, delay: 0.4 }}
                            viewport={{ once: true }}
                        >
                            <Button href="/register" size="lg" className="group bg-green-600 text-white shadow-lg hover:bg-green-700">
                                Daftar Sekarang Gratis
                                <ArrowRight className="ml-2 h-5 w-5 transition-transform group-hover:translate-x-1" />
                            </Button>

                            <div className="text-center">
                                <p className="mb-2 text-sm opacity-75">Atau hubungi kami langsung:</p>
                                <div className="flex flex-col gap-4 text-sm sm:flex-row">
                                    <span>📧 info@ciptamuri.co.id</span>
                                    <span>📱 +62 812-3456-7890</span>
                                </div>
                            </div>
                        </motion.div>

                        {/* Feature highlights */}
                        <motion.div
                            className="mt-16 grid grid-cols-2 gap-6 border-t border-green-500 pt-8 md:grid-cols-4"
                            initial={{ opacity: 0 }}
                            whileInView={{ opacity: 1 }}
                            transition={{ duration: 0.8, delay: 0.6 }}
                            viewport={{ once: true }}
                        >
                            <div className="text-center">
                                <div className="text-2xl font-bold">100%</div>
                                <div className="text-sm opacity-75">Transparan</div>
                            </div>
                            <div className="text-center">
                                <div className="text-2xl font-bold">24/7</div>
                                <div className="text-sm opacity-75">Support</div>
                            </div>
                            <div className="text-center">
                                <div className="text-2xl font-bold">0</div>
                                <div className="text-sm opacity-75">Biaya Admin</div>
                            </div>
                            <div className="text-center">
                                <div className="text-2xl font-bold">Gratis</div>
                                <div className="text-sm opacity-75">Pendaftaran</div>
                            </div>
                        </motion.div>
                    </div>
                </div>
            </section>
        </MainLayout>
    );
}
