import React, { useEffect } from 'react';

interface LoadingPageProps {
    message?: string;
    onComplete?: () => void;
    duration?: number;
}

export default function LoadingPage({ message, onComplete, duration = 3500 }: LoadingPageProps) {
    useEffect(() => {
        if (onComplete && duration > 0) {
            const timer = setTimeout(() => {
                onComplete();
            }, duration);

            return () => clearTimeout(timer);
        }
    }, [onComplete, duration]);
    return (
        <div className="min-h-screen bg-gradient-to-b from-[#84D61F] to-[#297694] flex flex-col items-center justify-center text-white relative overflow-hidden">
            
            {/* Main Content */}
            <div className="flex flex-col items-center justify-center flex-1 px-8 text-center relative z-10">
                {/* Logo - Centered and prominently displayed */}
                <div className="mb-2">
                    <div className="w-46 h-46 mx-auto mb-10 relative">
                        <img 
                            src="/logo.png" 
                            alt="Cipta Muri Logo" 
                            className="w-full h-full object-contain drop-shadow-2xl"
                            style={{animationDuration: '3s'}}
                        />
                    </div>
                </div>

                {/* Title */}
                <h1 className="loading-title text-4xl md:text-5xl font-black tracking-wider mb-2 animate-fadeIn" style={{animationDelay: '0.3s'}}>
                    CIPTA MURI
                </h1>

                {/* Subtitle */}
                <p className="loading-subtitle text-lg md:text-xl font-medium mb-8 animate-fadeIn leading-relaxed" style={{animationDelay: '0.5s'}}>
                    Sistem Bank Sampah Digital<br />
                    Desa Muntang
                </p>

                {/* Custom Message */}
                {message && (
                    <p className="loading-message text-base mb-4 opacity-90 animate-fadeIn" style={{animationDelay: '0.7s'}}>
                        {message}
                    </p>
                )}

                {/* Loading Spinner */}
                <div className="mb-1 animate-fadeIn" style={{animationDelay: '1s'}}>
                    <div className="w-12 h-12 border-4 border-white border-t-transparent rounded-full animate-spin shadow-lg"></div>
                </div>
            </div>

            {/* Footer */}
            <div className="pb-6 px-6 w-full">
                <div className="flex items-center justify-center">
                    <img 
                        src="/Logo-Footbar.png" 
                        alt="Footer Logos" 
                        className="h-16 object-contain opacity-90"
                    />
                </div>

            </div>
        </div>
    );
}
