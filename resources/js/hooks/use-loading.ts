import { useState, useCallback } from 'react';

export interface LoadingState {
    isLoading: boolean;
    message?: string;
}

export function useLoading(initialState: boolean = false) {
    const [loadingState, setLoadingState] = useState<LoadingState>({
        isLoading: initialState
    });

    const startLoading = useCallback((message?: string) => {
        setLoadingState({ isLoading: true, message });
    }, []);

    const stopLoading = useCallback(() => {
        setLoadingState({ isLoading: false });
    }, []);

    const setLoadingMessage = useCallback((message: string) => {
        setLoadingState(prev => ({ ...prev, message }));
    }, []);

    return {
        isLoading: loadingState.isLoading,
        message: loadingState.message,
        startLoading,
        stopLoading,
        setLoadingMessage
    };
}
