        import React from 'react';
import DashboardLayout from '@/layouts/dashboard/dashboard-layout';
import { DashboardHeader, SaldoCard, AdminMenuGrid, StatCard, RecentActivityItem } from '@/components/dashboard/admin/admin-components';
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";

interface AdminDashboardProps {
    user: {
        name: string;
        nik: string;
        role?: string;
    };
}

export default function AdminDashboard({ user }: AdminDashboardProps) {
    const stats = [
        {
            title: "Total Nasabah",
            value: "1,234",
            icon: (
                <svg className="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                </svg>
            ),
            trend: { value: "12%", isPositive: true }
        },
        {
            title: "Total Sampah (kg)",
            value: "15,678",
            icon: (
                <svg className="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            ),
            trend: { value: "8%", isPositive: true }
        },
        {
            title: "Total Transaksi",
            value: "2,456",
            icon: (
                <svg className="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                </svg>
            ),
            trend: { value: "5%", isPositive: false }
        },
        {
            title: "Revenue Bulan Ini",
            value: "Rp 45.6M",
            icon: (
                <svg className="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                </svg>
            ),
            trend: { value: "15%", isPositive: true }
        }
    ];

    const quickActions = [
        {
            title: "Kelola Nasabah",
            description: "Tambah, edit, hapus nasabah",
            icon: (
                <svg className="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                </svg>
            ),
            onClick: () => console.log('Kelola Nasabah')
        },
        {
            title: "Kelola Transaksi",
            description: "Monitor & validasi transaksi",
            icon: (
                <svg className="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                </svg>
            ),
            onClick: () => console.log('Kelola Transaksi')
        },
        {
            title: "Kelola Sampah",
            description: "Jenis & harga sampah",
            icon: (
                <svg className="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
            ),
            onClick: () => console.log('Kelola Sampah')
        },
        {
            title: "Laporan",
            description: "Generate laporan bulanan",
            icon: (
                <svg className="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            ),
            onClick: () => console.log('Laporan')
        },
        {
            title: "UMKM Management",
            description: "Kelola warung & kerjasama",
            icon: (
                <svg className="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            ),
            onClick: () => console.log('UMKM Management')
        },
        {
            title: "Pengaturan Sistem",
            description: "Konfigurasi aplikasi",
            icon: (
                <svg className="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
            ),
            onClick: () => console.log('Pengaturan Sistem')
        }
    ];

    const recentActivities = [
        {
            title: "Nasabah Baru Terdaftar",
            description: "Budi Santoso - NIK: 3201234567890123",
            time: "2 menit yang lalu",
            type: "success" as const
        },
        {
            title: "Transaksi Setor Sampah",
            description: "Siti Nurhaliza - 5kg sampah plastik",
            time: "15 menit yang lalu",
            type: "info" as const
        },
        {
            title: "Penarikan Saldo",
            description: "Ahmad Rahman - Rp 150.000",
            time: "1 jam yang lalu",
            type: "warning" as const
        },
        {
            title: "UMKM Order Baru",
            description: "Warung Bu Yeni - Pesanan makanan",
            time: "2 jam yang lalu",
            type: "info" as const
        }
    ];

    return (
        <DashboardLayout title="Admin Dashboard" user={user}>
            <div className="space-y-6 px-4">
                {/* Header dengan greeting */}
                <DashboardHeader userName={user.name} userNik={user.nik} />

                {/* Saldo Card */}
                <SaldoCard 
                    totalSaldo="Rp 1.250.000,00" 
                    totalSampah="156 Kg" 
                />

                {/* Menu Grid */}
                <AdminMenuGrid />

                {/* Statistics Card */}
                <Card className="bg-white/15 backdrop-blur-lg border-white/30 rounded-3xl">
                    <CardHeader>
                        <CardTitle className="text-white text-lg">Statistik Bank Sampah</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="grid grid-cols-2 gap-4">
                            {stats.slice(0, 4).map((stat, index) => (
                                <div key={index} className="text-center">
                                    <div className="text-white/80 mb-2">{stat.icon}</div>
                                    <div className="text-white text-xl font-bold">{stat.value}</div>
                                    <div className="text-white/70 text-sm">{stat.title}</div>
                                    {stat.trend && (
                                        <div className={`text-xs mt-1 ${stat.trend.isPositive ? 'text-green-300' : 'text-red-300'}`}>
                                            {stat.trend.isPositive ? '+' : '-'}{stat.trend.value}
                                        </div>
                                    )}
                                </div>
                            ))}
                        </div>
                    </CardContent>
                </Card>

                {/* Recent Activities */}
                <Card className="bg-white/15 backdrop-blur-lg border-white/30 rounded-3xl">
                    <CardHeader>
                        <CardTitle className="text-white text-lg">Aktivitas Terkini</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <div className="space-y-3">
                            {recentActivities.map((activity, index) => (
                                <RecentActivityItem
                                    key={index}
                                    title={activity.title}
                                    description={activity.description}
                                    time={activity.time}
                                    type={activity.type}
                                />
                            ))}
                        </div>
                    </CardContent>
                </Card>
            </div>
        </DashboardLayout>
    );
}
