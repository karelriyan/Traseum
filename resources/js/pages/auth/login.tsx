import { Head, Link, useForm } from '@inertiajs/react';
import { FormEventHandler, useState, useEffect } from 'react';
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

interface LoginProps {
    status?: string;
    canResetPassword?: boolean;
}

export default function Login({ status, canResetPassword }: LoginProps) {
    const [showError, setShowError] = useState(false);
    const { data, setData, post, processing, errors, reset } = useForm({
        nik: '',
        password: '',
        remember: false as boolean,
    });

    // Auto-hide error after 5 seconds
    useEffect(() => {
        if (Object.keys(errors).length > 0) {
            setShowError(true);
            const timer = setTimeout(() => {
                setShowError(false);
            }, 5000);
            
            return () => clearTimeout(timer);
        }
    }, [errors]);

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        
        // Validasi NIK 16 digit
        if (data.nik.length !== 16) {
            alert('NIK harus terdiri dari 16 digit');
            return;
        }
        
        // Reset error state
        setShowError(false);
        
        post(route('login'), {
            onFinish: () => reset('password'),
            onError: (errors) => {
                // Show error dengan delay untuk animasi
                setTimeout(() => {
                    setShowError(true);
                }, 100);
                
                // Handle specific error cases
                if (errors.nik) {
                    console.log('NIK error:', errors.nik);
                }
                if (errors.password) {
                    console.log('Password error:', errors.password);
                }
            }
        });
    };

    return (
        <>
            <Head title="Masuk">
                <link rel="preconnect" href="https://fonts.googleapis.com" />
                <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="anonymous" />
                <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
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

                    {/* Login Card */}
                    <Card className="bg-white/15 backdrop-blur-lg border-white/30 w-full max-w-xs md:max-w-sm relative overflow-hidden shadow-2xl rounded-3xl mx-4">
                        <CardHeader className="relative z-10 text-center">
                            <CardTitle className="loading-subtitle text-lg md:text-xl font-semibold text-white">
                                Masuk ke Akun Anda
                            </CardTitle>
                            <CardDescription className="text-white/80 text-sm">
                                Silakan masukkan NIK dan password
                            </CardDescription>
                        </CardHeader>

                        <CardContent className="relative z-10 pt-0 text-left">
                            {status && <div className="mb-4 font-medium text-sm text-green-300">{status}</div>}
                            
                            {/* Error Alert */}
                            {(errors.nik || errors.password || Object.keys(errors).length > 0) && (
                                <div className={`mb-4 p-3 bg-red-500/20 border border-red-500/30 rounded-lg backdrop-blur-sm transition-all`}>
                                    <div className="flex items-center space-x-2">
                                        <svg className="w-5 h-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fillRule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clipRule="evenodd" />
                                        </svg>
                                        <div className="text-red-400 text-sm font-medium">
                                            {errors.nik && (
                                                <div className="flex items-center space-x-1">
                                                    <span>NIK tidak ditemukan dalam sistem</span>
                                                </div>
                                            )}
                                            {errors.password && (
                                                <div className="flex items-center space-x-1">
                                                    <span>Password yang Anda masukkan salah</span>
                                                </div>
                                            )}
                                            {!errors.nik && !errors.password && Object.keys(errors).length > 0 && (
                                                <div className="flex items-center space-x-1">
                                                    <span>Login gagal - NIK atau password tidak cocok</span>
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                    
                                </div>
                            )}
                            
                            <form onSubmit={submit} className="space-y-5">
                                <div className="space-y-2">
                                    <Label htmlFor="nik" className="text-white font-medium">NIK</Label>
                                    <Input
                                        id="nik"
                                        type='text'
                                        name="nik"
                                        value={data.nik}
                                        className={`bg-white/20 border-white/30 text-white placeholder:text-white/60 focus:bg-white/30 transition-all duration-200 ${
                                            errors.nik ? 'border-red-400 bg-red-500/10 animate-shake' : ''
                                        }`}
                                        placeholder="1234567890123456"
                                        autoComplete="username"
                                        maxLength={16}
                                        onChange={(e) => {
                                            const value = e.target.value.replace(/\D/g, ''); // Hanya angka
                                            setData('nik', value);
                                            // Reset error state when user starts typing
                                            if (errors.nik) {
                                                setShowError(false);
                                            }
                                        }}
                                        onInput={(e) => {
                                            const target = e.target as HTMLInputElement;
                                            if (target.value.length < 16) {
                                                target.setCustomValidity('NIK harus terdiri dari 16 digit');
                                            } else {
                                                target.setCustomValidity('');
                                            }
                                        }}
                                        required
                                    />
                                    {data.nik.length > 0 && data.nik.length < 16 && (
                                        <div className="text-red-500 text-sm">NIK harus 16 digit ({data.nik.length}/16)</div>
                                    )}
                                    {errors.nik && <div className="text-red-500 text-sm">{errors.nik}</div>}
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="password" className="text-white font-medium">Password</Label>
                                    <Input
                                        id="password"
                                        type="password"
                                        name="password"
                                        value={data.password}
                                        className={`bg-white/20 border-white/30 text-white placeholder:text-white/60 focus:bg-white/30 transition-all duration-200 ${
                                            errors.password ? 'border-red-400 bg-red-500/10 animate-shake' : ''
                                        }`}
                                        placeholder="Masukkan password"
                                        autoComplete="current-password"
                                        onChange={(e) => {
                                            setData('password', e.target.value);
                                            // Reset error state when user starts typing
                                            if (errors.password) {
                                                setShowError(false);
                                            }
                                        }}
                                        required
                                    />
                                    {errors.password && <div className="text-red-500 text-sm">{errors.password}</div>}
                                </div>

                                <div className="flex items-center justify-between">
                                    <div className="flex items-center space-x-2">
                                        <input
                                            id="remember"
                                            type="checkbox"
                                            name="remember"
                                            checked={data.remember}
                                            onChange={(e) => setData('remember', e.target.checked)}
                                            className="rounded border-white/30 text-[#297694] shadow-sm focus:ring-[#297694]"
                                        />
                                        <Label htmlFor="remember" className="text-white/90 text-sm">
                                            Ingat saya
                                        </Label>
                                    </div>
                                    
                                    {canResetPassword && (
                                        <Link
                                            href={route('password.request')}
                                            className="text-white/90 hover:text-white text-sm underline"
                                        >
                                            Lupa password?
                                        </Link>
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
                                                <span>Memproses...</span>
                                            </div>
                                        ) : (
                                            'Masuk'
                                        )}
                                    </Button>

                                    
                                </div>
                            </form>
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
