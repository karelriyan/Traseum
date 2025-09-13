import { router } from '@inertiajs/react';

export function forceRefreshUserData() {
    // Force refresh current page to get updated user data
    router.reload({
        only: ['auth.user'],
    });
}

export function refreshAvatarCache() {
    // Add timestamp to force browser to reload images
    const avatarImages = document.querySelectorAll('img[alt*="avatar"], img[src*="avatar"]');
    avatarImages.forEach((img: Element) => {
        const imgElement = img as HTMLImageElement;
        const originalSrc = imgElement.src;
        if (originalSrc) {
            const url = new URL(originalSrc);
            url.searchParams.set('v', Date.now().toString());
            imgElement.src = url.toString();
        }
    });
}
