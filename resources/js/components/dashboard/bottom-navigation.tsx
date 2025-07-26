import React from 'react';
import { Link } from '@inertiajs/react';

interface BottomNavProps {
    currentPath?: string;
}

export default function BottomNavigation({ currentPath = '/' }: BottomNavProps) {
    const navItems = [
        {
            icon: (isActive: boolean) => (
                <svg className={`w-6 h-6 ${isActive ? 'text-white' : 'text-white/60'}`} fill="currentColor" viewBox="0 0 24 24">
                    <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                </svg>
            ),
            label: 'Home',
            path: '/dashboard'
        },
        {
            icon: (isActive: boolean) => (
                <svg className={`w-6 h-6 ${isActive ? 'text-white' : 'text-white/60'}`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            ),
            label: 'History',
            path: '/history'
        },
        {
            icon: (isActive: boolean) => (
                <div className={`w-12 h-12 rounded-full flex items-center justify-center ${isActive ? 'bg-white' : 'bg-white/20'}`}>
                    <svg className={`w-8 h-8 ${isActive ? 'text-[#297694]' : 'text-white'}`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h4M4 8h4m4 0V4M8 12V8M8 8H4m4 0h4m0 0v4m0 0h4m0 0v4" />
                    </svg>
                </div>
            ),
            label: 'QR',
            path: '/qr',
            isCenter: true
        },
        {
            icon: (isActive: boolean) => (
                <svg className={`w-6 h-6 ${isActive ? 'text-white' : 'text-white/60'}`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            ),
            label: 'Laporan',
            path: '/reports'
        },
        {
            icon: (isActive: boolean) => (
                <svg className={`w-6 h-6 ${isActive ? 'text-white' : 'text-white/60'}`} fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
            ),
            label: 'Profile',
            path: '/profile'
        }
    ];

    return (
        <div className="fixed bottom-0 left-0 right-0 bg-white/10 backdrop-blur-lg border-t border-white/20">
            <div className="max-w-md mx-auto">
                <div className="flex items-center justify-around py-2">
                    {navItems.map((item, index) => {
                        const isActive = currentPath === item.path;
                        
                        if (item.isCenter) {
                            return (
                                <Link
                                    key={index}
                                    href={item.path}
                                    className="flex flex-col items-center py-2 transition-all duration-200 hover:scale-105"
                                >
                                    {item.icon(isActive)}
                                </Link>
                            );
                        }
                        
                        return (
                            <Link
                                key={index}
                                href={item.path}
                                className="flex flex-col items-center py-2 px-3 transition-all duration-200 hover:scale-105"
                            >
                                {item.icon(isActive)}
                                <span className={`text-xs mt-1 ${isActive ? 'text-white font-medium' : 'text-white/60'}`}>
                                    {item.label}
                                </span>
                            </Link>
                        );
                    })}
                </div>
            </div>
        </div>
    );
}
