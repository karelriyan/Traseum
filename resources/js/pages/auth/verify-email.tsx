// Components
import { Head, useForm } from '@inertiajs/react';
import { LoaderCircle } from 'lucide-react';
import { FormEventHandler } from 'react';

import TextLink from '@/components/text-link';
import { Button } from '@/components/ui/button';
import AuthLayout from '@/layouts/auth-layout';

export default function VerifyEmail({ status }: { status?: string }) {
    const { post, processing } = useForm({});

    const submit: FormEventHandler = (e) => {
        e.preventDefault();

        post(route('verification.send'));
    };

    return (
        <AuthLayout title="Verify email" description="Please verify your email address by clicking on the link we just emailed to you.">
            <Head title="Email verification" />

            {status === 'verification-link-sent' && (
                <div className="mb-4 text-center text-sm font-medium text-green-600">
                    A new verification link has been sent to the email address you provided during registration.
                </div>
            )}

            <form onSubmit={submit} className="space-y-4 text-center">
                <Button 
                    disabled={processing} 
                    className="bg-gradient-to-r from-[#84D61F] to-[#297694] hover:from-[#297694] hover:to-[#84D61F] text-white font-semibold py-2 px-4 rounded-lg transition-all duration-300 shadow-lg"
                >
                    {processing && <LoaderCircle className="h-4 w-4 animate-spin mr-2" />}
                    Resend verification email
                </Button>

                <TextLink href={route('logout')} method="post" className="mx-auto block text-sm text-[#84D61F] hover:text-[#297694] font-medium">
                    Log out
                </TextLink>
            </form>
        </AuthLayout>
    );
}
