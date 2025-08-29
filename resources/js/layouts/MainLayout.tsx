import Button from '@/components/Button';
import { Link } from '@inertiajs/react';
import { Menu, X } from 'lucide-react';
import { ReactNode, SyntheticEvent, useEffect, useState } from 'react';

interface MainLayoutProps {
    children: ReactNode;
}

export default function MainLayout({ children }: MainLayoutProps) {
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);
    const [isScrolled, setIsScrolled] = useState(false);

    const navigation = [
        { name: 'Home', href: '#home' },
        { name: 'Tentang', href: '#tentang' },
        { name: 'Program', href: '#program' },
        { name: 'Cara Kerja', href: '#cara-kerja' },
        { name: 'Lokasi', href: '#lokasi' },
        { name: 'Kontak', href: '#kontak' },
    ];

    // Scroll detection effect
    useEffect(() => {
        const handleScroll = () => {
            const scrollPosition = window.scrollY;
            const heroSection = document.querySelector('#home') as HTMLElement;

            if (heroSection) {
                const heroHeight = heroSection.offsetHeight;
                setIsScrolled(scrollPosition > heroHeight * 0.1); // Change when scrolled 10% of hero height
            }
        };

        window.addEventListener('scroll', handleScroll);

        // Initial check
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
                        <div className="flex items-center space-x-3">
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
                        </div>

                        {/* Desktop Navigation */}
                        <div className="hidden items-center space-x-8 md:flex">
                            {navigation.map((item) => (
                                <button
                                    key={item.name}
                                    onClick={() => scrollToSection(item.href)}
                                    className={`transition-colors duration-300 ${
                                        isScrolled ? 'text-gray-700 hover:text-green-600' : 'text-white hover:text-green-300'
                                    }`}
                                >
                                    {item.name}
                                </button>
                            ))}
                        </div>

                        {/* Desktop CTA Buttons */}
                        <div className="hidden space-x-4 md:flex">
                            <Link href="/login">
                                <Button variant="outline" size="sm">
                                    Login
                                </Button>
                            </Link>
                            <Link href="/register">
                                <Button variant="primary" size="sm">
                                    Daftar
                                </Button>
                            </Link>
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
                                    <Link href="/login" className="flex-1">
                                        <Button variant="outline" size="sm" className="w-full">
                                            Login
                                        </Button>
                                    </Link>
                                    <Link href="/register" className="flex-1">
                                        <Button variant="primary" size="sm" className="w-full">
                                            Daftar
                                        </Button>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    )}
                </div>
            </nav>

            {/* Main Content */}
            <main>{children}</main>

            {/* Footer */}
            <footer className="bg-gray-900 text-white">
                <div className="container-custom py-12">
                    <div className="grid gap-8 md:grid-cols-4">
                        {/* Brand */}
                        <div className="space-y-4">
                            <div className="flex items-center space-x-3">
                                <img
                                    src="/logo.png"
                                    alt="Bank Sampah Cipta Muri"
                                    className="h-8 w-8"
                                    onError={(e: SyntheticEvent<HTMLImageElement>) => {
                                        (e.target as HTMLImageElement).style.display = 'none';
                                    }}
                                />
                                <div className="flex flex-col">
                                    <span className="font-bold text-green-400">Cipta Muri</span>
                                    <span className="text-xs text-gray-400">Bank Sampah</span>
                                </div>
                            </div>
                            <p className="text-gray-300">Mengubah sampah menjadi tabungan untuk masa depan yang lebih berkelanjutan.</p>
                        </div>

                        {/* Quick Links */}
                        <div>
                            <h3 className="mb-4 font-semibold">Menu</h3>
                            <div className="space-y-2">
                                {navigation.map((item) => (
                                    <button
                                        key={item.name}
                                        onClick={() => scrollToSection(item.href)}
                                        className="block text-gray-300 transition-colors hover:text-green-400"
                                    >
                                        {item.name}
                                    </button>
                                ))}
                            </div>
                        </div>

                        {/* Services */}
                        <div>
                            <h3 className="mb-4 font-semibold">Layanan</h3>
                            <div className="space-y-2 text-gray-300">
                                <p>Tabungan Sampah</p>
                                <p>Tukar Poin</p>
                                <p>Edukasi Lingkungan</p>
                                <p>Konsultasi</p>
                            </div>
                        </div>

                        {/* Contact */}
                        <div>
                            <h3 className="mb-4 font-semibold">Kontak</h3>
                            <div className="space-y-2 text-gray-300">
                                <p>📧 info@ciptamuri.co.id</p>
                                <p>📱 +62 812-3456-7890</p>
                                <p>📞 (021) 1234-5678</p>
                                <p>🏢 Jl. Raya Cilacap, Jawa Tengah</p>
                            </div>
                        </div>
                    </div>

                    <div className="mt-8 border-t border-gray-800 pt-8 text-center text-gray-400">
                        <p>&copy; 2024 Bank Sampah Cipta Muri. Semua hak dilindungi.</p>
                    </div>
                </div>
            </footer>
        </div>
    );
}
