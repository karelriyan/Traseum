import React from 'react';
import DashboardLayout from '@/layouts/dashboard/dashboard-layout';
import { BalanceCard, PointCard, ActionButton, UMKMCard } from '@/components/dashboard/nasabah/nasabah-components';
import BottomNavigation from '@/components/dashboard/bottom-navigation';

interface NasabahDashboardProps {
    user: {
        name: string;
        nik: string;
        role?: string;
    };
    saldo?: string;
    points?: string;
}

export default function NasabahDashboard({ 
    user, 
    saldo = "Rp 110.000,00", 
    points = "10 MP" 
}: NasabahDashboardProps) {
    
    const getGreeting = () => {
        const hour = new Date().getHours();
        if (hour < 12) return "Selamat Pagi";
        if (hour < 15) return "Selamat Siang";
        if (hour < 18) return "Selamat Sore";
        return "Selamat Malam";
    };

    const primaryActions = [
        {
            icon: (
                <svg className="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            ),
            title: "Setor Sampah",
            onClick: () => console.log('Setor Sampah')
        },
        {
            icon: (
                <svg className="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            ),
            title: "Mutasi Setoran",
            onClick: () => console.log('Mutasi Setoran')
        },
        {
            icon: (
                <svg className="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                </svg>
            ),
            title: "Tukar Saldo",
            onClick: () => console.log('Tukar Saldo')
        },
        {
            icon: (
                <svg className="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            ),
            title: "Riwayat Penarikan",
            onClick: () => console.log('Riwayat Penarikan')
        }
    ];

    const secondaryActions = [
        {
            icon: (
                <svg className="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
            ),
            title: "Donasi Sampah",
            onClick: () => console.log('Donasi Sampah')
        },
        {
            icon: (
                <svg className="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            ),
            title: "Leaderboard",
            onClick: () => console.log('Leaderboard')
        },
        {
            icon: (
                <svg className="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            ),
            title: "Katalog Kerjasama",
            onClick: () => console.log('Katalog Kerjasama')
        },
        {
            icon: (
                <svg className="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z" />
                </svg>
            ),
            title: "Lainnya",
            onClick: () => console.log('Lainnya')
        }
    ];

    return (
        <div className="min-h-screen bg-gradient-to-b from-[#84D61F] to-[#297694] pb-20">
            {/* Header dengan greeting */}
            <div className="bg-white/10 backdrop-blur-sm border-b border-white/20">
                <div className="max-w-md mx-auto px-4 py-4">
                    <div className="flex items-center space-x-3">
                        <div className="w-10 h-10 bg-white/20 rounded-full flex items-center justify-center">
                            <span className="text-white font-semibold text-lg">
                                {user.name.charAt(0).toUpperCase()}
                            </span>
                        </div>
                        <div>
                            <p className="text-white/80 text-sm">{getGreeting()},</p>
                            <p className="text-white font-semibold text-lg">{user.name.split(' ')[0]}</p>
                            <p className="text-white/70 text-xs">Mau tukar sampah apa hari ini?</p>
                        </div>
                    </div>
                </div>
            </div>

            {/* Main Content */}
            <div className="max-w-md mx-auto px-4 py-6">
                {/* Balance Card */}
                <BalanceCard saldo={saldo} nik={user.nik} />

                {/* Points Card */}
                <PointCard points={points} />

                {/* Primary Actions Grid */}
                <div className="grid grid-cols-2 gap-3 mb-6">
                    {primaryActions.map((action, index) => (
                        <ActionButton
                            key={index}
                            icon={action.icon}
                            title={action.title}
                            onClick={action.onClick}
                        />
                    ))}
                </div>

                {/* Secondary Actions Grid */}
                <div className="grid grid-cols-4 gap-3 mb-6">
                    {secondaryActions.map((action, index) => (
                        <ActionButton
                            key={index}
                            icon={action.icon}
                            title={action.title}
                            onClick={action.onClick}
                        />
                    ))}
                </div>

                {/* UMKM Section */}
                <div className="mb-6">
                    <h3 className="text-white font-semibold text-lg mb-3">UMKM Desa Muntang</h3>
                    <UMKMCard
                        name="Warung Bu Yeni"
                        description="(50 m)"
                    />
                </div>

                {/* Footer Logo */}
                <div className="flex items-center justify-center mt-8 mb-4">
                    <img
                        src="/Logo-Footbar.png"
                        alt="Footer Logos"
                        className="h-12 object-contain opacity-90"
                    />
                </div>
            </div>

            {/* Bottom Navigation */}
            <BottomNavigation currentPath="/dashboard" />
        </div>
    );
}
