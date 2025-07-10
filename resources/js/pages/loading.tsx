import React, { useState } from 'react';
import { Head } from '@inertiajs/react';
import LoadingPage from '@/components/loading-page';
import Welcome from './welcome';

export default function Loading() {
    const [showWelcome, setShowWelcome] = useState(false);

    const handleComplete = () => {
        // Setelah loading selesai, tampilkan welcome page
        setShowWelcome(true);
    };

    // Jika loading selesai, tampilkan welcome page
    if (showWelcome) {
        return <Welcome />;
    }

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
