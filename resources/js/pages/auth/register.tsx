import { Head, useForm } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { FormEventHandler } from 'react';

import InputError from '@/components/input-error';
import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AuthLayout from '@/layouts/auth-layout';

type RegisterForm = {
    name: string;
    nik: string;
    password: string;
    password_confirmation: string;
};

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm<Required<RegisterForm>>({
        name: '',
        nik: '',
        password: '',
        password_confirmation: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('register'), {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    return (
        <AuthLayout title="Create an account" description="Enter your details below to create your account">
            <Head title="Register" />
            <form className="flex flex-col gap-4" onSubmit={submit}>
                <div className="grid gap-4">
                    <div className="grid gap-2">
                        <Label htmlFor="name" className="text-gray-700 font-medium">Name</Label>
                        <Input
                            id="name"
                            type="text"
                            required
                            autoFocus
                            tabIndex={1}
                            autoComplete="name"
                            value={data.name}
                            onChange={(e) => setData('name', e.target.value)}
                            disabled={processing}
                            placeholder="Full name"
                            className="bg-white/80 border-gray-300 text-gray-900 placeholder-gray-500 focus:border-[#84D61F] focus:ring-[#84D61F]"
                        />
                        <InputError message={errors.name} className="mt-2" />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="nik" className="text-gray-700 font-medium">NIK</Label>
                        <Input
                            id="nik"
                            type="text"
                            required
                            tabIndex={2}
                            autoComplete="username"
                            value={data.nik}
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
                            disabled={processing}
                            placeholder="1234567890123456"
                            className="bg-white/80 border-gray-300 text-gray-900 placeholder-gray-500 focus:border-[#84D61F] focus:ring-[#84D61F]"
                        />
                        {data.nik.length > 0 && data.nik.length < 16 && (
                            <div className="text-red-500 text-sm">NIK harus 16 digit ({data.nik.length}/16)</div>
                        )}
                        <InputError message={errors.nik} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="password" className="text-gray-700 font-medium">Password</Label>
                        <Input
                            id="password"
                            type="password"
                            required
                            tabIndex={3}
                            autoComplete="new-password"
                            value={data.password}
                            onChange={(e) => setData('password', e.target.value)}
                            disabled={processing}
                            placeholder="Password"
                            className="bg-white/80 border-gray-300 text-gray-900 placeholder-gray-500 focus:border-[#84D61F] focus:ring-[#84D61F]"
                        />
                        <InputError message={errors.password} />
                    </div>

                    <div className="grid gap-2">
                        <Label htmlFor="password_confirmation" className="text-gray-700 font-medium">Confirm password</Label>
                        <Input
                            id="password_confirmation"
                            type="password"
                            required
                            tabIndex={4}
                            autoComplete="new-password"
                            value={data.password_confirmation}
                            onChange={(e) => setData('password_confirmation', e.target.value)}
                            disabled={processing}
                            placeholder="Confirm password"
                            className="bg-white/80 border-gray-300 text-gray-900 placeholder-gray-500 focus:border-[#84D61F] focus:ring-[#84D61F]"
                        />
                        <InputError message={errors.password_confirmation} />
                    </div>

                    <Button 
                        type="submit" 
                        className="mt-4 w-full bg-gradient-to-r from-[#84D61F] to-[#297694] hover:from-[#297694] hover:to-[#84D61F] text-white font-semibold py-2 px-4 rounded-lg transition-all duration-300 shadow-lg" 
                        tabIndex={5} 
                        disabled={processing}
                    >
                        {processing && <LoaderCircle className="h-4 w-4 animate-spin mr-2" />}
                        Create account
                    </Button>
                </div>

                <div className="text-center text-sm text-gray-600 mt-4">
                    Already have an account?{' '}
                    <TextLink href={route('login')} tabIndex={6} className="text-[#84D61F] hover:text-[#297694] font-medium">
                        Log in
                    </TextLink>
                </div>
            </form>
        </AuthLayout>
    );
}
