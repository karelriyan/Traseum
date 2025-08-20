import { Link } from '@inertiajs/react';
import { Menu, X } from 'lucide-react';
import { useState } from 'react';

export default function MainLayout({ children }) {
    const [mobileMenuOpen, setMobileMenuOpen] = useState(false);

    const navigation = [
        { name: 'Home', href: '#home' },
        { name: 'Tentang', href: '#tentang' },
        { name: 'Program', href: '#program' },
        { name: 'Cara Kerja', href: '#cara-kerja' },
        { name: 'Lokasi', href: '#lokasi' },
        { name: 'Kontak', href: '#kontak' },
    ];

    const scrollToSection = (href) => {
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
            <nav className="sticky top-0 z-50 bg-white shadow-lg">
                <div className="container-custom">
                    <div className="flex items-center justify-between py-4">
                        {/* Logo */}
                        <div className="flex items-center space-x-3">
                            <img
                                src="/logo.png"
                                alt="Bank Sampah Cipta Muri"
                                className="h-10 w-10"
                                onError={(e) => {
                                    e.target.style.display = 'none';
                                }}
                            />
                            <div className="flex flex-col">
                                <span className="text-xl font-bold text-green-600">Cipta Muri</span>
                                <span className="text-xs text-gray-500">Bank Sampah</span>
                            </div>
                        </div>

                        {/* Desktop Navigation */}
                        <div className="hidden items-center space-x-8 md:flex">
                            {navigation.map((item) => (
                                <button
                                    key={item.name}
                                    onClick={() => scrollToSection(item.href)}
                                    className="font-medium text-gray-700 transition-colors duration-200 hover:text-green-600"
                                >
                                    {item.name}
                                </button>
                            ))}
                        </div>

                        {/* Action Buttons */}
                        <div className="hidden items-center space-x-4 md:flex">
                            <Link href="/register" className="btn-outline">
                                Daftar Sekarang
                            </Link>
                            <Link href="/login" className="btn-primary">
                                Login
                            </Link>
                        </div>

                        {/* Mobile menu button */}
                        <button
                            onClick={() => setMobileMenuOpen(!mobileMenuOpen)}
                            className="p-2 text-gray-700 hover:text-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 md:hidden"
                            aria-label="Toggle mobile menu"
                        >
                            {mobileMenuOpen ? <X className="h-6 w-6" /> : <Menu className="h-6 w-6" />}
                        </button>
                    </div>

                    {/* Mobile Navigation */}
                    {mobileMenuOpen && (
                        <div className="border-t border-gray-200 bg-white md:hidden">
                            <div className="space-y-4 py-4">
                                {navigation.map((item) => (
                                    <button
                                        key={item.name}
                                        onClick={() => scrollToSection(item.href)}
                                        className="block w-full px-4 py-2 text-left font-medium text-gray-700 transition-colors duration-200 hover:text-green-600"
                                    >
                                        {item.name}
                                    </button>
                                ))}
                                <div className="space-y-3 border-t border-gray-200 px-4 pt-4">
                                    <Link href="/register" className="btn-outline block w-full text-center">
                                        Daftar Sekarang
                                    </Link>
                                    <Link href="/login" className="btn-primary block w-full text-center">
                                        Login
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
                    <div className="space-y-8">
                        {/* Company Info */}
                        <div className="text-center">
                            <div className="flex items-center justify-center space-x-3">
                                <img
                                    src="/logo.png"
                                    alt="Bank Sampah Cipta Muri"
                                    className="h-8 w-8"
                                    onError={(e) => {
                                        e.target.style.display = 'none';
                                    }}
                                />
                                <span className="text-lg font-bold">Bank Sampah Cipta Muri</span>
                            </div>
                            <p className="mt-4 text-sm text-gray-400">
                                Mengubah sampah menjadi tabungan untuk masa depan yang lebih hijau dan berkelanjutan.
                            </p>
                        </div>

                        {/* Contact & Location */}
                        <div className="text-center">
                            <div className="mb-4 flex flex-wrap justify-center gap-4 text-sm text-gray-400">
                                <span>📧 info@ciptamuri.co.id</span>
                                <span>📞 (021) 1234-5678</span>
                                <span>📱 +62 812-3456-7890</span>
                            </div>
                            <p className="text-sm text-gray-400">Jl. Raya Cilacap, Jawa Tengah, Indonesia</p>
                        </div>

                        {/* Quick Links */}
                        <div className="text-center">
                            <div className="flex flex-wrap justify-center gap-4">
                                {navigation.map((item) => (
                                    <button
                                        key={item.name}
                                        onClick={() => scrollToSection(item.href)}
                                        className="text-sm text-gray-400 transition-colors duration-200 hover:text-white"
                                    >
                                        {item.name}
                                    </button>
                                ))}
                            </div>
                        </div>
                    </div>

                    <div className="mt-8 border-t border-gray-800 pt-8 text-center">
                        <p className="text-sm text-gray-400">© 2025 Bank Sampah Cipta Muri. All rights reserved.</p>
                    </div>
                </div>
            </footer>
        </div>
    );
}
