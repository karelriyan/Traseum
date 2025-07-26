import React, { useState } from 'react';
import { Head, router } from '@inertiajs/react';
import LoadingPage from '@/components/loading-page';

export default function Loading() {
    const [isCompleted, setIsCompleted] = useState(false);

    const handleComplete = () => {
        // Setelah loading selesai, redirect ke login route yang proper
        setIsCompleted(true);
        router.get('/login' );
    };

    // Tampilkan loading page
    return (
        <>
            <LoadingPage 
                message="Memuat sistem..."
                onComplete={handleComplete}
                duration={2000}
            />
        </>
    );
}
