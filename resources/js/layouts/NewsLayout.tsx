import { Link } from '@inertiajs/react';
import { ArrowLeft } from 'lucide-react';
import { ReactNode } from 'react';

interface NewsLayoutProps {
    children: ReactNode;
    showBackButton?: boolean;
    backUrl?: string;
    backLabel?: string;
}

export default function NewsLayout({ children, showBackButton = true, backUrl = '/', backLabel = 'Kembali ke Beranda' }: NewsLayoutProps) {
    return (
        <div className="min-h-screen bg-gray-50">
            {/* Simple Header with Back Button */}
            {showBackButton && (
                <header className="sticky top-0 z-50 border-b border-gray-200 bg-white shadow-sm">
                    <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div className="flex h-16 items-center justify-between">
                            <Link
                                href={backUrl}
                                className="inline-flex items-center gap-2 rounded-lg px-4 py-2 font-medium text-gray-600 transition-all duration-200 hover:bg-green-50 hover:text-green-600"
                            >
                                <ArrowLeft className="h-5 w-5" />
                                {backLabel}
                            </Link>

                            <Link href="/" className="flex items-center gap-3 transition-transform duration-200 hover:scale-105">
                                <div className="flex items-center space-x-3">
                                    <img
                                        src="/logo.png"
                                        alt="Bank Sampah Cipta Muri"
                                        className="h-10 w-10"
                                        onError={(e) => {
                                            const target = e.target as HTMLImageElement;
                                            target.style.display = 'none';
                                        }}
                                    />
                                    <div className="flex flex-col">
                                        <span className="text-xl font-bold text-green-600">Cipta Muri</span>
                                        <span className="text-xs text-gray-500">e-Bank Sampah</span>
                                    </div>
                                </div>
                            </Link>
                        </div>
                    </div>
                </header>
            )}

            {/* Main Content */}
            <main className="flex-1">{children}</main>

            {/* Simple Footer */}
            <footer className="mt-16 border-t border-gray-200 bg-white">
                <div className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
                    <div className="text-center">
                        <p className="text-gray-600 transition-colors duration-300 hover:text-gray-800">
                            © 2025 Bank Sampah Cipta Muri. All rights reserved.
                        </p>
                        <div className="mt-4 flex justify-center gap-6">
                            <Link
                                href="/"
                                className="group relative text-sm text-gray-500 transition-all duration-300 hover:translate-y-[-2px] hover:text-green-600"
                            >
                                <span className="relative">
                                    Beranda
                                    <span className="absolute bottom-0 left-0 h-0.5 w-0 bg-green-600 transition-all duration-300 group-hover:w-full"></span>
                                </span>
                            </Link>
                            <Link
                                href="/berita"
                                className="group relative text-sm text-gray-500 transition-all duration-300 hover:translate-y-[-2px] hover:text-green-600"
                            >
                                <span className="relative">
                                    Berita
                                    <span className="absolute bottom-0 left-0 h-0.5 w-0 bg-green-600 transition-all duration-300 group-hover:w-full"></span>
                                </span>
                            </Link>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    );
}
