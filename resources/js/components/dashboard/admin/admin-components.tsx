import React from 'react';
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Recycle, BarChart, DollarSign, History, Heart, Trophy, ShoppingBag, MoreHorizontal } from 'lucide-react';

interface StatCardProps {
    title: string;
    value: string;
    icon: React.ReactNode;
    trend?: {
        value: string;
        isPositive: boolean;
    };
}

// Header dengan greeting dan profil
export function DashboardHeader({ userName, userNik }: { userName: string; userNik: string }) {
    return (
        <div className="flex items-center justify-between mb-6">
            <div className="flex items-center space-x-4">
                <div className="w-12 h-12 bg-blue-600 rounded-full flex items-center justify-center">
                    <span className="text-white font-bold text-lg">{userName.charAt(0)}</span>
                </div>
                <div>
                    <h1 className="text-white text-xl font-semibold">Halo,</h1>
                    <h2 className="text-white text-2xl font-bold">{userName}</h2>
                    <p className="text-white/70 text-sm">Kelola Bank Sampah Hari ini?</p>
                </div>
            </div>
        </div>
    );
}

// Card saldo dengan desain dari gambar
export function SaldoCard({ totalSaldo, totalSampah }: { totalSaldo: string; totalSampah: string }) {
    return (
        <Card className="relative overflow-hidden bg-white rounded-3xl shadow-lg mb-6">
            {/* Background motif */}
            <div 
                className="absolute inset-0"
                style={{
                    backgroundImage: 'url(/assets/motif-card.png)',
                    backgroundSize: '30%',
                    backgroundPosition: 'right top',
                    backgroundRepeat: 'no-repeat'
                }}
            />
            
            <CardContent className="relative z-10 p-6">
                <div className="flex justify-between items-start">
                    <div>
                        <h3 className="text-gray-700 text-lg font-semibold mb-2 sm:text-xl">Saldo Rekening</h3>
                        <p className="text-gray-500 text-sm mb-1">Total Bank Sampah</p>
                        <div className="text-xl font-bold text-gray-900 mb-4 sm:text-4xl">{totalSaldo}</div>
                        <p className="text-gray-600 text-sm">Total sampah terjual: {totalSampah}</p>
                    </div>
                    <div className="mt-45">
                        <button className="text-blue-500 hover:text-blue-700 transition-colors text-sm font-medium">
                            Cek mutasi &gt;
                        </button>
                    </div>
                </div>
            </CardContent>
        </Card>
    );
}

// Menu grid seperti di gambar
export function AdminMenuGrid() {
    const menuItems = [
        {
            icon: <Recycle size={24} />,
            title: "Setor",
            subtitle: "Sampah",
            color: "bg-blue-100",
            iconColor: "text-blue-600"
        },
        {
            icon: <BarChart size={24} />,
            title: "Riwayat",
            subtitle: "Setoran", 
            color: "bg-green-100",
            iconColor: "text-green-600"
        },
        {
            icon: <DollarSign size={24} />,
            title: "Tarik",
            subtitle: "Saldo",
            color: "bg-blue-100",
            iconColor: "text-blue-600"
        },
        {
            icon: <History size={24} />,
            title: "Riwayat",
            subtitle: "Penarikan",
            color: "bg-blue-100", 
            iconColor: "text-blue-600"
        },
        {
            icon: <Heart size={24} />,
            title: "Donasi",
            subtitle: "Sampah",
            color: "bg-green-100",
            iconColor: "text-green-600"
        },
        {
            icon: <Trophy size={24} />,
            title: "Leaderboard",
            subtitle: "",
            color: "bg-green-100",
            iconColor: "text-green-600"
        },
        {
            icon: <ShoppingBag size={24} />,
            title: "Katalog",
            subtitle: "Kerajinan",
            color: "bg-blue-100",
            iconColor: "text-blue-600"
        },
        {
            icon: <MoreHorizontal size={24} />,
            title: "Lainnya",
            subtitle: "",
            color: "bg-blue-100",
            iconColor: "text-blue-600"
        }
    ];

    return (
        <div className="grid grid-cols-4 gap-6 py-4">
            {menuItems.map((item, index) => (
                <div key={index} className="flex flex-col items-center">
                    <div className={`w-20 h-20 ${item.color} rounded-2xl flex items-center justify-center mb-3 hover:scale-105 transition-transform cursor-pointer shadow-sm`}>
                        <span className={item.iconColor}>{item.icon}</span>
                    </div>
                    <div className="text-center">
                        <p className="text-white text-sm font-medium leading-tight">{item.title}</p>
                        {item.subtitle && (
                            <p className="text-white/70 text-xs leading-tight">{item.subtitle}</p>
                        )}
                    </div>
                </div>
            ))}
        </div>
    );
}

export function StatCard({ title, value, icon, trend }: StatCardProps) {
    return (
        <Card className="bg-white/15 backdrop-blur-lg border-white/30 hover:bg-white/20 transition-all duration-200">
            <CardHeader className="flex flex-row items-center justify-between space-y-0 pb-2">
                <CardTitle className="text-sm font-medium text-white/90">{title}</CardTitle>
                <div className="text-white/80">{icon}</div>
            </CardHeader>
            <CardContent>
                <div className="text-2xl font-bold text-white">{value}</div>
                {trend && (
                    <p className={`text-xs ${trend.isPositive ? 'text-green-300' : 'text-red-300'} mt-1`}>
                        {trend.isPositive ? '+' : '-'}{trend.value} dari bulan lalu
                    </p>
                )}
            </CardContent>
        </Card>
    );
}

export function QuickActionCard({ title, description, icon, onClick }: {
    title: string;
    description: string;
    icon: React.ReactNode;
    onClick: () => void;
}) {
    return (
        <Card 
            className="bg-white/15 backdrop-blur-lg border-white/30 hover:bg-white/20 transition-all duration-200 cursor-pointer hover:scale-105"
            onClick={onClick}
        >
            <CardContent className="flex flex-col items-center p-6 text-center">
                <div className="text-white/80 mb-3">{icon}</div>
                <h3 className="text-white font-semibold text-sm mb-1">{title}</h3>
                <p className="text-white/70 text-xs">{description}</p>
            </CardContent>
        </Card>
    );
}

export function RecentActivityItem({ 
    title, 
    description, 
    time, 
    type 
}: {
    title: string;
    description: string;
    time: string;
    type: 'success' | 'warning' | 'info';
}) {
    const typeStyles = {
        success: 'bg-green-500/20 text-green-300',
        warning: 'bg-yellow-500/20 text-yellow-300',
        info: 'bg-blue-500/20 text-blue-300'
    };

    return (
        <div className="flex items-start space-x-3 p-3 bg-white/10 rounded-lg backdrop-blur-sm">
            <div className={`w-2 h-2 rounded-full mt-2 ${typeStyles[type].split(' ')[0]}`}></div>
            <div className="flex-1">
                <h4 className="text-white font-medium text-sm">{title}</h4>
                <p className="text-white/70 text-xs">{description}</p>
                <span className="text-white/50 text-xs">{time}</span>
            </div>
        </div>
    );
}
