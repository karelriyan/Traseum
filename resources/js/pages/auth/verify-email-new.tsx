import { Head, Link, useForm } from '@inertiajs/react';
import { FormEventHandler } from 'react';
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from "@/components/ui/card"
import { Button } from "@/components/ui/button"

interface VerifyEmailProps {
    status?: string;
}

export default function VerifyEmail({ status }: VerifyEmailProps) {
    const { post, processing } = useForm({});

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('verification.send'));
    };

    return (
        <>
            <Head title="Verifikasi Email - Cipta Muri">
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

                    {/* Verify Email Card */}
                    <Card className="bg-white/15 backdrop-blur-lg border-white/30 w-full max-w-xs md:max-w-sm relative overflow-hidden shadow-2xl rounded-3xl mx-4">
                        <CardHeader className="relative z-10 text-center">
                            <CardTitle className="loading-subtitle text-lg md:text-xl font-semibold text-white">
                                Verifikasi Email
                            </CardTitle>
                            <CardDescription className="text-white/80 text-sm">
                                Kami telah mengirimkan link verifikasi ke email Anda. Silakan cek email dan klik link tersebut untuk melanjutkan.
                            </CardDescription>
                        </CardHeader>

                        <CardContent className="relative z-10 pt-0">
                            {status === 'verification-link-sent' && (
                                <div className="mb-4 font-medium text-sm text-green-300">
                                    Link verifikasi baru telah dikirim ke alamat email yang Anda daftarkan.
                                </div>
                            )}

                            <form onSubmit={submit} className="space-y-4">
                                <div className="space-y-3">
                                    <Button
                                        type="submit"
                                        disabled={processing}
                                        className="w-full bg-white text-[#297694] font-semibold py-3 md:py-4 px-4 md:px-6 rounded-xl hover:bg-gray-100 hover:scale-105 transition-all duration-200 shadow-lg font-poppins text-sm md:text-base"
                                    >
                                        {processing ? 'Mengirim...' : 'Kirim Ulang Email Verifikasi'}
                                    </Button>

                                    <div className="text-center">
                                        <Link
                                            href={route('logout')}
                                            method="post"
                                            as="button"
                                            className="text-white/90 hover:text-white text-sm underline"
                                        >
                                            Keluar
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
