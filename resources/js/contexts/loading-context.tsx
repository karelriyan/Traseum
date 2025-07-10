import React, { createContext, useContext, ReactNode } from 'react';
import { useLoading, LoadingState } from '@/hooks/use-loading';
import LoadingOverlay from '@/components/loading-overlay';

interface LoadingContextType {
    isLoading: boolean;
    message?: string;
    startLoading: (message?: string) => void;
    stopLoading: () => void;
    setLoadingMessage: (message: string) => void;
}

const LoadingContext = createContext<LoadingContextType | undefined>(undefined);

interface LoadingProviderProps {
    children: ReactNode;
}

export function LoadingProvider({ children }: LoadingProviderProps) {
    const loading = useLoading();

    return (
        <LoadingContext.Provider value={loading}>
            {children}
            <LoadingOverlay 
                isVisible={loading.isLoading} 
                message={loading.message}
                onComplete={loading.stopLoading}
            />
        </LoadingContext.Provider>
    );
}

export function useLoadingContext() {
    const context = useContext(LoadingContext);
    if (context === undefined) {
        throw new Error('useLoadingContext must be used within a LoadingProvider');
    }
    return context;
}
