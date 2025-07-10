import React from 'react';
import LoadingPage from './loading-page';

interface LoadingOverlayProps {
    isVisible: boolean;
    message?: string;
    onComplete?: () => void;
}

export default function LoadingOverlay({ isVisible, message, onComplete }: LoadingOverlayProps) {
    if (!isVisible) return null;

    return (
        <div className="fixed inset-0 z-50">
            <LoadingPage message={message} onComplete={onComplete} />
        </div>
    );
}
