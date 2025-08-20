import { Head } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { ArrowRight, CheckCircle, Leaf, PiggyBank, Recycle, Shield, Smartphone, Users } from 'lucide-react';

import Button from '../components/Button';
import ProgramCard from '../components/ProgramCard';
import SectionHeader from '../components/SectionHeader';
import StatCard from '../components/StatCard';
import StepItem from '../components/StepItem';
import TestimonialItem from '../components/TestimonialItem';
import MainLayout from '../layouts/MainLayout';
        
export default function Home({ stats, programs, testimonials }) {
    return (
        <MainLayout>
            <Head>
                <title>Bank Sampah Cipta Muri - Ubah Sampah Jadi Tabungan</title>
                <meta
                    name="description"
                    content="Bergabunglah dengan Bank Sampah Cipta Muri untuk mengubah sampah menjadi tabungan dan menciptakan lingkungan yang bersih dan berkelanjutan."
                />
            </Head>

            {/* Hero Section */}
            <section id="home" className="relative flex min-h-screen items-center overflow-hidden">
                {/* Background Image */}
                <div 
                    className="absolute inset-0 bg-cover bg-center bg-no-repeat"
                >
                    {/* Overlay */}
                    <div className="absolute inset-0 bg-gradient-to-br from-green-900/80 via-green-800/70 to-yellow-600/60"></div>
                </div>

                {/* Background Pattern */}
                <div className="absolute inset-0 opacity-10">
                    <div className="absolute left-20 top-20 h-72 w-72 animate-pulse rounded-full bg-white mix-blend-multiply blur-xl filter"></div>
                    <div className="animation-delay-2000 absolute right-20 top-40 h-72 w-72 animate-pulse rounded-full bg-yellow-100 mix-blend-multiply blur-xl filter"></div>
                    <div className="animation-delay-4000 absolute bottom-20 left-1/2 h-72 w-72 animate-pulse rounded-full bg-green-100 mix-blend-multiply blur-xl filter"></div>
                </div>

                <div className="container-custom relative z-10">
                    <div className="grid min-h-[80vh] grid-cols-1 items-center gap-12 lg:grid-cols-2">
                        {/* Content */}
                        <motion.div
                            className="text-center lg:text-left"
                            initial={{ opacity: 0, x: -50 }}
                            animate={{ opacity: 1, x: 0 }}
                            transition={{ duration: 0.8 }}
                        >

                            <motion.h1
                                className="mb-6 text-4xl font-bold leading-tight text-white md:text-5xl lg:text-6xl drop-shadow-lg"
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
                                    <ArrowRight className="ml-2 h-5 w-5 transition-transform text-white group-hover:translate-x-1" />
                                </Button>
                                <Button
                                    variant="outline"
                                    size="lg"
                                    onClick={() => {
                                        document.querySelector('#tentang').scrollIntoView({ behavior: 'smooth' });
                                    }}
                                >
                                    Pelajari Lebih Lanjut
                                </Button>
                            </motion.div>

                            {/* Trust Indicators */}
                            <motion.div
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
                        <motion.div
                            className="relative"
                            initial={{ opacity: 0, x: 50 }}
                            animate={{ opacity: 1, x: 0 }}
                            transition={{ duration: 0.8, delay: 0.2 }}
                        >
                            <img src="/images/AI-2.png" alt="Hero" className="w-2/3 mx-auto" />
                        </motion.div>
                    </div>
                </div>
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
                    <SectionHeader
                        subtitle="Tentang Kami"
                        title="3 Pilar Utama Bank Sampah Cipta Muri"
                        description="Kami berkomitmen untuk menciptakan solusi pengelolaan sampah yang berkelanjutan melalui teknologi modern dan pendekatan yang transparan."
                    />

                    <div className="mt-16 grid grid-cols-1 gap-8 md:grid-cols-3">
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
                            <h3 className="mb-4 text-2xl font-bold text-gray-900">Transparan</h3>
                            <p className="leading-relaxed text-gray-600">
                                Sistem tracking yang jelas memungkinkan Anda memantau setiap transaksi dan perkembangan tabungan secara real-time
                                dengan detail yang lengkap.
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
                            <h3 className="mb-4 text-2xl font-bold text-gray-900">Ramah Lingkungan</h3>
                            <p className="leading-relaxed text-gray-600">
                                Mendukung program daur ulang dan pengelolaan sampah berkelanjutan untuk menciptakan lingkungan yang bersih dan sehat
                                bagi generasi mendatang.
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
                            <h3 className="mb-4 text-2xl font-bold text-gray-900">Berbasis Digital</h3>
                            <p className="leading-relaxed text-gray-600">
                                Platform modern yang mudah digunakan dengan teknologi terdepan untuk memberikan pengalaman pengguna yang optimal dan
                                efisien.
                            </p>
                        </motion.div>
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
                                number={1}
                                title="Kumpulkan"
                                description="Pisahkan sampah organik dan anorganik di rumah Anda. Pastikan sampah dalam kondisi bersih dan kering untuk mendapatkan nilai terbaik."
                                icon="Trash2"
                                delay={0.1}
                            />
                            <StepItem
                                number={2}
                                title="Setor"
                                description="Bawa sampah yang sudah dipilah ke lokasi Bank Sampah Cipta Muri terdekat atau jadwalkan pickup melalui aplikasi mobile kami."
                                icon="TrendingUp"
                                delay={0.2}
                            />
                            <StepItem
                                number={3}
                                title="Dapat Poin"
                                description="Sistem konversi otomatis akan menghitung nilai sampah dan langsung menambahkan poin ke akun tabungan digital Anda."
                                icon="HandCoins"
                                delay={0.3}
                            />
                            <StepItem
                                number={4}
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

            {/* Location Section */}
            <section id="lokasi" className="section-padding bg-gray-50">
                <div className="container-custom">
                    <SectionHeader
                        title="Lokasi Bank Sampah"
                        subtitle="Kunjungi langsung kantor Bank Sampah Cipta Muri untuk konsultasi dan pelayanan terbaik"
                        center
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
                            style={{border: 0}} 
                            allowFullScreen="" 
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
                            <h3 className="mb-4 text-xl font-semibold text-gray-800">Bank Sampah Cipta Muri</h3>
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
                            <Button href="/register" size="lg" className="group bg-white text-green-600 shadow-lg hover:bg-gray-50">
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