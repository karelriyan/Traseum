import React, { ReactNode } from 'react';
import { Head } from '@inertiajs/react';

interface DashboardLayoutProps {
    children: ReactNode;
    title?: string;
    user?: {
        name: string;
        nik: string;
        role?: string;
    };
}

export default function DashboardLayout({ children, title = "Dashboard", user }: DashboardLayoutProps) {
    return (
        <>
            <Head title={title}>
                <link rel="preconnect" href="https://fonts.googleapis.com" />
                <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="anonymous" />
                <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
                <style>{`
                    body {
                        font-family: 'Poppins', sans-serif;
                        background: linear-gradient(to bottom, #84D61F, #297694);
                        min-height: 100vh;
                    }
                `}</style>
            </Head>

            <div className="min-h-screen bg-gradient-to-b from-[#84D61F] to-[#297694]">
                {/* Header */}
                <div className="bg-white/10 backdrop-blur-sm border-b border-white/20">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex items-center justify-between h-16">
                            {/* Logo */}
                            <div className="flex items-center space-x-3">
                                <img
                                    src="/logo.png"
                                    alt="Cipta Muri Logo"
                                    className="h-10 w-10 object-contain"
                                />
                                <div>
                                    <h1 className="text-white font-bold text-lg">CIPTA MURI</h1>
                                    <p className="text-white/80 text-xs">Bank Sampah Digital</p>
                                </div>
                            </div>

                            {/* User Info */}
                            {user && (
                                <div className="flex items-center space-x-4">
                                    <div className="text-right">
                                        <p className="text-white font-medium text-sm">{user.name}</p>
                                        <p className="text-white/80 text-xs">NIK: {user.nik}</p>
                                    </div>
                                    <div className="h-8 w-8 bg-white/20 rounded-full flex items-center justify-center">
                                        <span className="text-white font-semibold text-sm">
                                            {user.name.charAt(0).toUpperCase()}
                                        </span>
                                    </div>
                                </div>
                            )}
                        </div>
                    </div>
                </div>

                {/* Main Content */}
                <main className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    {children}
                </main>

                {/* Footer */}
                <div className="mt-auto pb-6 px-6 w-full">
                    <div className="flex items-center justify-center">
                        <img
                            src="/Logo-Footbar.png"
                            alt="Footer Logos"
                            className="h-12 object-contain opacity-90"
                        />
                    </div>
                </div>
            </div>
        </>
    );
}
