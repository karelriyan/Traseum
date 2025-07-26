import React from 'react';
import { Card, CardContent, CardHeader, CardTitle } from "@/components/ui/card";
import { Button } from "@/components/ui/button";

interface BalanceCardProps {
    saldo: string;
    nik: string;
}

export function BalanceCard({ saldo, nik }: BalanceCardProps) {
    return (
        <Card className="bg-white/15 backdrop-blur-lg border-white/30 mb-4">
            <CardContent className="p-6">
                <div className="flex items-center justify-between">
                    <div>
                        <p className="text-white/80 text-sm">Saldo Rekening</p>
                        <p className="text-white text-xs opacity-75">{nik}</p>
                        <p className="text-white text-2xl font-bold mt-1">{saldo}</p>
                        <p className="text-white/70 text-xs">Total sampah terjual: 25 kg</p>
                    </div>
                    <Button 
                        variant="outline" 
                        size="sm"
                        className="bg-white/20 border-white/30 text-white hover:bg-white/30"
                    >
                        Cek mutasi →
                    </Button>
                </div>
            </CardContent>
        </Card>
    );
}

interface PointCardProps {
    points: string;
}

export function PointCard({ points }: PointCardProps) {
    return (
        <Card className="bg-white/15 backdrop-blur-lg border-white/30 mb-6">
            <CardContent className="p-4">
                <div className="flex items-center justify-between">
                    <div className="flex items-center space-x-3">
                        <div className="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center">
                            <span className="text-white text-xs font-bold">⭐</span>
                        </div>
                        <div>
                            <p className="text-white font-medium">Poin Muri: {points}</p>
                        </div>
                    </div>
                    <Button 
                        variant="outline" 
                        size="sm"
                        className="bg-white/20 border-white/30 text-white hover:bg-white/30 text-xs"
                    >
                        Tukar point →
                    </Button>
                </div>
            </CardContent>
        </Card>
    );
}

interface ActionButtonProps {
    icon: React.ReactNode;
    title: string;
    onClick: () => void;
}

export function ActionButton({ icon, title, onClick }: ActionButtonProps) {
    return (
        <button 
            onClick={onClick}
            className="flex flex-col items-center p-4 bg-white/15 backdrop-blur-lg border border-white/30 rounded-2xl hover:bg-white/20 transition-all duration-200 hover:scale-105"
        >
            <div className="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center mb-2">
                {icon}
            </div>
            <span className="text-white text-xs font-medium text-center leading-tight">{title}</span>
        </button>
    );
}

interface UMKMCardProps {
    name: string;
    description: string;
    image?: string;
}

export function UMKMCard({ name, description, image }: UMKMCardProps) {
    return (
        <Card className="bg-gradient-to-r from-red-500 to-yellow-500 border-0 overflow-hidden">
            <CardContent className="p-4">
                <div className="flex items-center justify-between">
                    <div className="flex-1">
                        <h3 className="text-white font-bold text-sm mb-1">UMKM Desa Muntang</h3>
                        <h4 className="text-white font-semibold">{name}</h4>
                        <p className="text-white/90 text-xs">{description}</p>
                    </div>
                    <div className="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                        <span className="text-white">🏪</span>
                    </div>
                </div>
            </CardContent>
        </Card>
    );
}
