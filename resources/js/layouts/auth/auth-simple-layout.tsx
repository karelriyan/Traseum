import { Link } from '@inertiajs/react';
import { type PropsWithChildren } from 'react';

interface AuthLayoutProps {
    name?: string;
    title?: string;
    description?: string;
}

export default function AuthSimpleLayout({ children, title, description }: PropsWithChildren<AuthLayoutProps>) {
    return (
        <div className="min-h-screen bg-gradient-to-b from-[#84D61F] to-[#297694] flex flex-col items-center justify-center text-white relative overflow-hidden font-['Poppins']">
            
            {/* Main Content */}
            <div className="flex flex-col items-center justify-center flex-1 px-8 text-center relative z-10">
                {/* Logo */}
                <div className="mb-6">
                    <Link href="/" className="block">
                        <div className="w-32 h-32 mx-auto mb-4 relative">
                            <img 
                                src="/logo.png" 
                                alt="Cipta Muri Logo" 
                                className="w-full h-full object-contain drop-shadow-2xl"
                            />
                        </div>
                    </Link>
                </div>

                {/* Title */}
                <h1 className="text-3xl md:text-4xl font-black tracking-wider mb-2 text-white">
                    CIPTA MURI
                </h1>

                {/* Subtitle */}
                <p className="text-base md:text-lg font-semibold mb-8 tracking-wide leading-relaxed text-white/90">
                    Sistem Bank Sampah Digital<br />
                    Desa Muntang
                </p>

                {/* Auth Card */}
                <div className="w-full max-w-md mx-auto">
                    <div className="bg-white/10 backdrop-blur-lg rounded-2xl border border-white/20 shadow-2xl p-8">
                        <div className="space-y-4 text-center mb-6">
                            <h2 className="text-xl font-bold text-white">{title}</h2>
                            <p className="text-sm text-white/80">{description}</p>
                        </div>
                        <div className="text-left">
                            {children}
                        </div>
                    </div>
                </div>
            </div>

            {/* Footer */}
            <div className="pb-6 px-6 w-full">
                <div className="flex items-center justify-center">
                    <img 
                        src="/Logo-Footbar.png" 
                        alt="Footer Logos" 
                        className="h-12 object-contain opacity-90"
                    />
                </div>
            </div>
        </div>
    );
}
