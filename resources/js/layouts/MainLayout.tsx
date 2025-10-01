import { SimpleUserDropdown } from '@/components/simple-user-dropdown';
import { type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { Menu, X } from 'lucide-react';
import { ReactNode, SyntheticEvent, useEffect, useState } from 'react';

interface MainLayoutProps {
    children: ReactNode;
}

export default function MainLayout({ children }: MainLayoutProps) {
    const { auth } = usePage<SharedData>().props;
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
    const [isScrolled, setIsScrolled] = useState(false);
    const [activeSection, setActiveSection] = useState('home');

    const navigation = [
        { name: 'Home', href: '#home' },
        { name: 'Tentang', href: '#tentang' },
        { name: 'Cara Kerja', href: '#cara-kerja' },
        { name: 'Program', href: '#program' },
        { name: 'Testimoni', href: '#testimoni' },
        { name: 'Berita & Publikasi', href: '#berita-publikasi' },
        { name: 'Lokasi', href: '#lokasi' },
        { name: 'Kontak', href: '#kontak' },
    ];

    // Scroll detection effect & active section
    useEffect(() => {
        const handleScroll = () => {
            const scrollPosition = window.scrollY;
            const sectionIds = ['home', 'tentang', 'cara-kerja', 'program', 'testimoni', 'berita-publikasi', 'lokasi', 'kontak'];
            let currentSection = 'home';
            for (const id of sectionIds) {
                const section = document.getElementById(id);
                if (section) {
                    const offsetTop = section.offsetTop - 80; // adjust for navbar height
                    if (scrollPosition >= offsetTop) {
                        currentSection = id;
                    }
                }
            }
            setActiveSection(currentSection);

            const heroSection = document.querySelector('#home') as HTMLElement;
            if (heroSection) {
                const heroHeight = heroSection.offsetHeight;
                setIsScrolled(scrollPosition > heroHeight * 0.1);
            }
        };
        window.addEventListener('scroll', handleScroll);
        handleScroll();
        return () => {
            window.removeEventListener('scroll', handleScroll);
        };
    }, []);

    const scrollToSection = (href: string) => {
        if (href.startsWith('#')) {
            const element = document.querySelector(href);
            if (element) {
                element.scrollIntoView({ behavior: 'smooth' });
            }
        }
        setMobileMenuOpen(false);
    };

    return (
        <div className="min-h-screen bg-white">
            {/* Navigation */}
            <nav
                className={`fixed top-0 z-50 w-full transition-all duration-300 ${
                    isScrolled ? 'bg-white/95 shadow-lg backdrop-blur-md' : 'bg-transparent'
                }`}
            >
                <div className="container-custom">
                    <div className="flex items-center justify-between py-4">
                        {/* Logo */}
                        <Link href="/" className="flex items-center space-x-3 transition-transform duration-200 hover:scale-105">
                            <img
                                src="/logo.png"
                                alt="Bank Sampah Cipta Muri"
                                className="h-10 w-10"
                                onError={(e: SyntheticEvent<HTMLImageElement>) => {
                                    (e.target as HTMLImageElement).style.display = 'none';
                                }}
                            />
                            <div className="flex flex-col">
                                <span className={`text-xl font-bold transition-colors duration-300 ${isScrolled ? 'text-green-600' : 'text-white'}`}>
                                    Cipta Muri
                                </span>
                                <span className={`text-xs transition-colors duration-300 ${isScrolled ? 'text-gray-500' : 'text-white/70'}`}>
                                    e-Bank Sampah
                                </span>
                            </div>
                        </Link>

                        {/* Desktop Navigation */}
                        <div className="hidden items-center space-x-8 md:flex">
                            {navigation.map((item) => (
                                <button
                                    key={item.name}
                                    onClick={() => scrollToSection(item.href)}
                                    className={`relative transition-colors duration-300 ${
                                        activeSection === item.href.replace('#', '')
                                            ? 'text-green-500'
                                            : isScrolled
                                              ? 'text-gray-700 hover:text-green-600'
                                              : 'text-white hover:text-green-300'
                                    }`}
                                >
                                    {item.name}
                                </button>
                            ))}
                        </div>

                        {/* Desktop CTA Buttons */}
                        <div className="hidden space-x-4 md:flex">
                            {auth.user ? (
                                <SimpleUserDropdown
                                    user={auth.user}
                                    variant="navbar"
                                    className={`transition-colors duration-300 ${
                                        isScrolled
                                            ? 'border-green-200 bg-green-50 hover:bg-green-100'
                                            : 'border-white/20 bg-white/10 hover:bg-white/20'
                                    }`}
                                />
                            ) : (
                                <button
                                    onClick={() => (window.location.href = '/admin')}
                                    className="inline-flex items-center justify-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                >
                                    Login
                                </button>
                            )}
                            {/* <Link href="/register">
                                <Button variant="primary" size="sm">
                                    Daftar
                                </Button>
                            </Link> */}
                        </div>

                        {/* Mobile Menu Button */}
                        <button
                            className={`transition-colors duration-300 md:hidden ${isScrolled ? 'text-gray-700' : 'text-white'}`}
                            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                        >
                            {mobileMenuOpen ? <X className="h-6 w-6" /> : <Menu className="h-6 w-6" />}
                        </button>
                    </div>

                    {/* Mobile Navigation */}
                    {mobileMenuOpen && (
                        <div className="border-t border-gray-200 pb-3 pt-2 md:hidden">
                            <div className="space-y-1">
                                {navigation.map((item) => (
                                    <button
                                        key={item.name}
                                        onClick={() => scrollToSection(item.href)}
                                        className="block w-full px-3 py-2 text-left text-base font-medium text-gray-700 hover:bg-gray-50 hover:text-green-600"
                                    >
                                        {item.name}
                                    </button>
                                ))}
                                <div className="flex space-x-2 px-3 pt-2">
                                    {auth.user ? (
                                        <div className="flex w-full items-center justify-center">
                                            <SimpleUserDropdown user={auth.user} className="border-green-200 bg-green-50 hover:bg-green-100" />
                                        </div>
                                    ) : (
                                        <button
                                            onClick={() => (window.location.href = '/admin')}
                                            className="inline-flex flex-1 items-center justify-center rounded-md border border-gray-300 bg-green-600 px-4 py-2 text-sm font-medium text-white transition-colors hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
                                        >
                                            Login
                                        </button>
                                    )}
                                    {/* <Link href="/register" className="flex-1">
                                        <Button variant="primary" size="sm" className="w-full">
                                            Daftar
                                        </Button>
                                    </Link> */}
                                </div>
                            </div>
                        </div>
                    )}
                </div>
            </nav>

            {/* Main Content */}
            <main>{children}</main>

            {/* Footer */}
            <footer className="bg-gray-900 text-white" style={{ backgroundColor: '#111827', color: '#ffffff' }}>
                <div className="container-custom py-12">
                    <div className="grid gap-8 md:grid-cols-4">
                        {/* Brand */}
                        <div className="space-y-4">
                            <div className="group flex items-center space-x-3 transition-all duration-300 hover:scale-105">
                                <img
                                    src="/logo.png"
                                    alt="Bank Sampah Cipta Muri"
                                    className="h-8 w-8 transition-transform duration-300 group-hover:rotate-6"
                                    onError={(e: SyntheticEvent<HTMLImageElement>) => {
                                        (e.target as HTMLImageElement).style.display = 'none';
                                    }}
                                />
                                <div className="flex flex-col">
                                    <span className="font-bold text-green-400 transition-colors duration-300 group-hover:text-green-300">
                                        Cipta Muri
                                    </span>
                                    <span className="text-xs text-gray-400 transition-colors duration-300 group-hover:text-gray-300">
                                        Bank Sampah
                                    </span>
                                </div>
                            </div>
                            <p className="text-gray-300 transition-colors duration-300 hover:text-gray-200">
                                Mengubah sampah menjadi tabungan untuk masa depan yang lebih berkelanjutan.
                            </p>
                        </div>

                        {/* Quick Links */}
                        <div>
                            <h3 className="mb-4 font-semibold text-white transition-colors duration-300 hover:text-green-400">Menu</h3>
                            <div className="space-y-2">
                                {navigation.map((item) => (
                                    <button
                                        key={item.name}
                                        onClick={() => scrollToSection(item.href)}
                                        className="group block text-gray-300 transition-all duration-300 hover:translate-x-2 hover:text-green-400"
                                    >
                                        <span className="relative">
                                            {item.name}
                                            <span className="absolute bottom-0 left-0 h-0.5 w-0 bg-green-400 transition-all duration-300 group-hover:w-full"></span>
                                        </span>
                                    </button>
                                ))}
                            </div>
                        </div>

                        {/* Services */}
                        <div>
                            <h3 className="mb-4 font-semibold text-white transition-colors duration-300 hover:text-green-400">Layanan</h3>
                            <div className="space-y-2 text-gray-300">
                                <p className="group cursor-pointer transition-all duration-300 hover:translate-x-2 hover:text-green-400">
                                    <span className="relative">
                                        Tabungan Sampah
                                        <span className="absolute bottom-0 left-0 h-0.5 w-0 bg-green-400 transition-all duration-300 group-hover:w-full"></span>
                                    </span>
                                </p>
                                <p className="group cursor-pointer transition-all duration-300 hover:translate-x-2 hover:text-green-400">
                                    <span className="relative">
                                        Tukar Poin
                                        <span className="absolute bottom-0 left-0 h-0.5 w-0 bg-green-400 transition-all duration-300 group-hover:w-full"></span>
                                    </span>
                                </p>
                                <p className="group cursor-pointer transition-all duration-300 hover:translate-x-2 hover:text-green-400">
                                    <span className="relative">
                                        Edukasi Lingkungan
                                        <span className="absolute bottom-0 left-0 h-0.5 w-0 bg-green-400 transition-all duration-300 group-hover:w-full"></span>
                                    </span>
                                </p>
                                <p className="group cursor-pointer transition-all duration-300 hover:translate-x-2 hover:text-green-400">
                                    <span className="relative">
                                        Konsultasi
                                        <span className="absolute bottom-0 left-0 h-0.5 w-0 bg-green-400 transition-all duration-300 group-hover:w-full"></span>
                                    </span>
                                </p>
                            </div>
                        </div>

                        {/* Contact */}
                        <div>
                            <h3 className="mb-4 font-semibold text-white transition-colors duration-300 hover:text-green-400">Kontak</h3>
                            <div className="space-y-2 text-gray-300">
                                <p className="group flex cursor-pointer items-center transition-all duration-300 hover:translate-x-2 hover:text-green-400">
                                    <span className="mr-2 transition-transform duration-300 group-hover:scale-125">📧</span>
                                    <span className="relative">
                                        info@ciptamuri.co.id
                                        <span className="absolute bottom-0 left-0 h-0.5 w-0 bg-green-400 transition-all duration-300 group-hover:w-full"></span>
                                    </span>
                                </p>
                                <p className="group flex cursor-pointer items-center transition-all duration-300 hover:translate-x-2 hover:text-green-400">
                                    <span className="mr-2 transition-transform duration-300 group-hover:scale-125">📱</span>
                                    <span className="relative">
                                        +62 812-3456-7890
                                        <span className="absolute bottom-0 left-0 h-0.5 w-0 bg-green-400 transition-all duration-300 group-hover:w-full"></span>
                                    </span>
                                </p>
                                <p className="group flex cursor-pointer items-center transition-all duration-300 hover:translate-x-2 hover:text-green-400">
                                    <span className="mr-2 transition-transform duration-300 group-hover:scale-125">📞</span>
                                    <span className="relative">
                                        (021) 1234-5678
                                        <span className="absolute bottom-0 left-0 h-0.5 w-0 bg-green-400 transition-all duration-300 group-hover:w-full"></span>
                                    </span>
                                </p>
                                <p className="group flex cursor-pointer items-center transition-all duration-300 hover:translate-x-2 hover:text-green-400">
                                    <span className="mr-2 transition-transform duration-300 group-hover:scale-125">🏢</span>
                                    <span className="relative">
                                        Jl. Raya Cilacap, Jawa Tengah
                                        <span className="absolute bottom-0 left-0 h-0.5 w-0 bg-green-400 transition-all duration-300 group-hover:w-full"></span>
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div className="mt-8 border-t border-gray-800 pt-8 text-center text-gray-400">
                        <p className="transition-colors duration-300 hover:text-gray-300">
                            &copy; 2024 Bank Sampah Cipta Muri. Semua hak dilindungi.
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    );
}
