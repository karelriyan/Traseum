import { Head, Link, useForm } from '@inertiajs/react';
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

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        nik: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        
        // Validasi NIK 16 digit
        if (data.nik.length !== 16) {
            alert('NIK harus terdiri dari 16 digit');
            return;
        }
        
        post(route('register'), {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    return (
        <>
            <Head title="Daftar - Cipta Muri">
                <link rel="preconnect" href="https://fonts.googleapis.com" />
                <link rel="preconnect" href="https://fonts.gstatic.com" crossOrigin="anonymous" />
                <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet" />
            </Head>

            <div className="min-h-screen bg-gradient-to-b from-[#84D61F] to-[#297694] flex flex-col items-center justify-center text-white relative overflow-hidden">

                {/* Main Content */}
                <div className="flex flex-col items-center justify-center flex-1 px-8 text-center relative z-10 w-full">
                    {/* Logo and Title - Responsive Layout */}
                    <div className="mb-8 flex flex-col md:flex-row items-center justify-center md:space-x-6 space-y-6 md:space-y-0">
                        {/* Logo */}
                        <div className="w-20 h-20 md:w-24 md:h-24 flex-shrink-0">
                            <img
                                src="/logo.png"
                                alt="Cipta Muri Logo"
                                className="w-full h-full object-contain drop-shadow-2xl"
                            />
                        </div>
                        
                        {/* Vertical Divider - Only on desktop */}
                        <div className="hidden md:block w-px h-16 bg-white/50"></div>
                        
                        {/* Title and Subtitle */}
                        <div className="text-center md:text-left">
                            <h1 className="loading-title text-2xl md:text-3xl lg:text-4xl font-black mb-2 text-white">
                                CIPTA MURI
                            </h1>
                            <p className="loading-subtitle text-xs md:text-sm lg:text-base font-medium text-white/90 leading-tight">
                                Sistem Bank Sampah Digital<br/>
                                Desa Muntang
                            </p>
                        </div>
                    </div>

                    {/* Register Card */}
                    <Card className="bg-white/15 backdrop-blur-lg border-white/30 w-full max-w-xs md:max-w-sm relative overflow-hidden shadow-2xl rounded-3xl mx-4">
                        <CardHeader className="relative z-10 text-center">
                            <CardTitle className="loading-subtitle text-lg md:text-xl font-semibold text-white">
                                Buat Akun Baru
                            </CardTitle>
                            <CardDescription className="text-white/80 text-sm">
                                Bergabunglah dengan komunitas peduli lingkungan
                            </CardDescription>
                        </CardHeader>

                        <CardContent className="relative z-10 pt-0">
                            <form onSubmit={submit} className="space-y-4">
                                <div className="space-y-2">
                                    <Label htmlFor="name" className="text-white font-medium">Nama Lengkap</Label>
                                    <Input
                                        id="name"
                                        type="text"
                                        name="name"
                                        value={data.name}
                                        className="bg-white/20 border-white/30 text-white placeholder:text-white/60 focus:bg-white/30"
                                        placeholder="Masukkan nama lengkap"
                                        autoComplete="name"
                                        onChange={(e) => setData('name', e.target.value)}
                                        required
                                    />
                                    {errors.name && <div className="text-red-300 text-sm">{errors.name}</div>}
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="nik" className="text-white font-medium">NIK (16 digit)</Label>
                                    <Input
                                        id="nik"
                                        type="text"
                                        name="nik"
                                        value={data.nik}
                                        className="bg-white/20 border-white/30 text-white placeholder:text-white/60 focus:bg-white/30"
                                        placeholder="1234567890123456"
                                        autoComplete="off"
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
                                        required
                                    />
                                    {data.nik.length > 0 && data.nik.length < 16 && (
                                        <div className="text-yellow-300 text-sm">NIK harus 16 digit ({data.nik.length}/16)</div>
                                    )}
                                    {errors.nik && <div className="text-red-300 text-sm">{errors.nik}</div>}
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="email" className="text-white font-medium">Email</Label>
                                    <Input
                                        id="email"
                                        type="email"
                                        name="email"
                                        value={data.email}
                                        className="bg-white/20 border-white/30 text-white placeholder:text-white/60 focus:bg-white/30"
                                        placeholder="contoh@email.com"
                                        autoComplete="username"
                                        onChange={(e) => setData('email', e.target.value)}
                                        required
                                    />
                                    {errors.email && <div className="text-red-300 text-sm">{errors.email}</div>}
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="password" className="text-white font-medium">Password</Label>
                                    <Input
                                        id="password"
                                        type="password"
                                        name="password"
                                        value={data.password}
                                        className="bg-white/20 border-white/30 text-white placeholder:text-white/60 focus:bg-white/30"
                                        placeholder="Masukkan password"
                                        autoComplete="new-password"
                                        onChange={(e) => setData('password', e.target.value)}
                                        required
                                    />
                                    {errors.password && <div className="text-red-300 text-sm">{errors.password}</div>}
                                </div>

                                <div className="space-y-2">
                                    <Label htmlFor="password_confirmation" className="text-white font-medium">Konfirmasi Password</Label>
                                    <Input
                                        id="password_confirmation"
                                        type="password"
                                        name="password_confirmation"
                                        value={data.password_confirmation}
                                        className="bg-white/20 border-white/30 text-white placeholder:text-white/60 focus:bg-white/30"
                                        placeholder="Ulangi password"
                                        autoComplete="new-password"
                                        onChange={(e) => setData('password_confirmation', e.target.value)}
                                        required
                                    />
                                    {errors.password_confirmation && <div className="text-red-300 text-sm">{errors.password_confirmation}</div>}
                                </div>

                                <div className="space-y-3">
                                    <Button
                                        type="submit"
                                        disabled={processing}
                                        className="w-full bg-white text-[#297694] font-semibold py-3 md:py-4 px-4 md:px-6 rounded-xl hover:bg-gray-100 hover:scale-105 transition-all duration-200 shadow-lg font-poppins text-sm md:text-base"
                                    >
                                        {processing ? 'Memproses...' : 'Daftar'}
                                    </Button>

                                    <div className="text-center">
                                        <span className="text-white/80 text-sm">Sudah punya akun? </span>
                                        <Link
                                            href={route('login')}
                                            className="text-white font-semibold hover:underline text-sm"
                                        >
                                            Masuk di sini
                                        </Link>
                                    </div>
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
