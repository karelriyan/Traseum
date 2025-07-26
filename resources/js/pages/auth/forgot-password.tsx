// Components
import { Head, useForm } from '@inertiajs/react';
import { FormEventHandler } from 'react';
import {
    Card,
    CardContent,
    CardDescription,
    CardHeader,
    CardTitle,
} from "@/components/ui/card"
import { Input } from "@/components/ui/input"
import { Label } from "@/components/ui/label"
import { Button } from "@/components/ui/button"
import TextLink from '@/components/text-link';

export default function ForgotPassword({ status }: { status?: string }) {
    const { data, setData, post, processing, errors } = useForm<Required<{ nik: string }>>({
        nik: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        post(route('password.email'));
    };

    return (
        <>
            <Head title="Lupa Password">
                <link rel="preconnect" href="https://fonts.googleapis.com" />
                <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="anonymous" />
                <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
                <style>{`
                    @keyframes shake {
                        0%, 100% { transform: translateX(0); }
                        10%, 30%, 50%, 70%, 90% { transform: translateX(-2px); }
                        20%, 40%, 60%, 80% { transform: translateX(2px); }
                    }
                    .animate-shake {
                        animation: shake 0.5s ease-in-out;
                    }
                    body {
                        font-family: 'Poppins', sans-serif;
                    }
                `}</style>
            </Head>

            <div className="min-h-screen bg-gradient-to-b from-[#84D61F] to-[#297694] flex flex-col items-center justify-center text-white relative overflow-hidden">

                {/* Main Content */}
                <div className="flex flex-col items-center justify-center flex-1 px-6 text-center relative z-10 w-full">
                    {/* Logo and Title - Responsive Layout */}
                    <div className="mb-8 flex flex-col md:flex-row items-center justify-center md:space-x-6 space-y-4 md:space-y-0">
                        {/* Logo */}
                        <div className="w-20 h-20 md:w-24 md:h-24 flex-shrink-0">
                            <img
                                src="/logo.png"
                                alt="Cipta Muri Logo"
                                className="w-full h-full object-contain drop-shadow-2xl"
                            />
                        </div>
                        
                        {/* Vertical Divider - Only on desktop */}
                        <div className="hidden md:block w-px h-20 bg-white"></div>
                        
                        {/* Title and Subtitle */}
                        <div className="text-center md:text-left">
                            <h1 className="loading-title text-2xl md:text-3xl lg:text-4xl font-black mb-1 text-white">
                                CIPTA MURI
                            </h1>
                            <p className="loading-subtitle text-xs md:text-sm lg:text-base font-medium text-white/90 ">
                                Sistem Bank Sampah Digital<br/>
                                Desa Muntang
                            </p>
                        </div>
                    </div>

                    {/* Forgot Password Card */}
                    <Card className="bg-white/15 backdrop-blur-lg border-white/30 w-full max-w-xs md:max-w-sm relative overflow-hidden shadow-2xl rounded-3xl mx-4">
                        <CardHeader className="relative z-10 text-center">
                            <CardTitle className="loading-subtitle text-lg md:text-xl font-semibold text-white">
                                Lupa Password?
                            </CardTitle>
                            <CardDescription className="text-white/80 text-sm">
                                Masukkan email Anda untuk menerima link reset password
                            </CardDescription>
                        </CardHeader>

                        <CardContent className="relative z-10 pt-0 text-left">
                            {status && <div className="mb-4 p-3 bg-white-500/50 border border-green-500/30 rounded-lg backdrop-blur-sm">
                                <div className="flex items-center space-x-2">
                                    <svg className="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clipRule="evenodd" />
                                    </svg>
                                    <div className="text-green-600 text-sm font-medium">{status}</div>
                                </div>
                            </div>}

                            {/* Error Alert */}
                            {errors.nik && (
                                <div className="mb-4 p-3 bg-red-500/20 border border-red-500/30 rounded-lg backdrop-blur-sm transition-all">
                                    <div className="flex items-center space-x-2">
                                        <svg className="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clipRule="evenodd" />
                                        </svg>
                                        <div className="text-red-400 text-sm font-medium">{errors.nik}</div>
                                    </div>
                                </div>
                            )}

                            <form onSubmit={submit} className="space-y-5">
                                <div className="space-y-2">
                                    <Label htmlFor="nik" className="text-white font-medium">NIK</Label>
                                    <Input
                                        id="nik"
                                        type="text"
                                        name="nik"
                                        autoComplete="username"
                                        value={data.nik}
                                        autoFocus
                                        maxLength={16}
                                        onChange={(e) => {
                                            const value = e.target.value.replace(/\D/g, ''); // Hanya angka
                                            setData('nik', value);
                                        }}
                                        onInput={(e) => {
                                            const target = e.target as HTMLInputElement;
                                            if (target.value.length < 16) {
                                                target.setCustomValidity('NIK harus terdiri dari 16 digit');
                                            } else {
                                                target.setCustomValidity('');
                                            }
                                        }}
                                        placeholder="1234567890123456"
                                        className={`bg-white/20 border-white/30 text-white placeholder:text-white/60 focus:bg-white/30 transition-all duration-200 ${
                                            errors.nik ? 'border-red-400 bg-red-500/10 animate-shake' : ''
                                        }`}
                                        required
                                    />
                                    {data.nik.length > 0 && data.nik.length < 16 && (
                                        <div className="text-red-500 text-sm">NIK harus 16 digit ({data.nik.length}/16)</div>
                                    )}
                                </div>

                                <div className="space-y-3">
                                    <Button
                                        type="submit"
                                        disabled={processing}
                                        className={`w-full bg-white text-[#297694] font-semibold py-3 md:py-4 px-4 md:px-6 rounded-xl hover:bg-gray-100 hover:scale-105 transition-all duration-200 shadow-lg font-poppins text-sm md:text-base ${
                                            processing ? 'opacity-75 cursor-not-allowed' : ''
                                        }`}
                                    >
                                        {processing ? (
                                            <div className="flex items-center justify-center space-x-2">
                                                <svg className="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                                    <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" strokeWidth="4"></circle>
                                                    <path className="opacity-75" fill="currentColor" d="m4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                </svg>
                                                <span>Mengirim...</span>
                                            </div>
                                        ) : (
                                            'Kirim Link Reset Password'
                                        )}
                                    </Button>
                                </div>
                            </form>

                            <div className="text-center text-sm text-white/80 mt-4">
                                <span>Atau, kembali ke </span>
                                <TextLink href={route('login')} className="text-white hover:text-white/80 font-medium underline">
                                    login
                                </TextLink>
                            </div>
                        </CardContent>
                    </Card>
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
        </>
    );
}
