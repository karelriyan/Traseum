<!-- Lightbox Modal -->
<div id="image-lightbox" class="fixed inset-0 z-50 hidden bg-black bg-opacity-90 p-4" style="display: none;">
    <div class="flex items-center justify-center h-full">
        <div class="relative max-w-7xl max-h-full">
        <!-- Close Button -->
        <button 
            id="close-lightbox" 
            class="absolute -top-12 right-0 text-white hover:text-gray-300 text-2xl font-bold p-2 z-10"
            aria-label="Close"
        >
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        
        <!-- Loading Spinner -->
        <div id="lightbox-loader" class="absolute inset-0 flex items-center justify-center">
            <div class="animate-spin rounded-full h-32 w-32 border-b-2 border-white"></div>
        </div>
        
        <!-- Image -->
        <img 
            id="lightbox-image" 
            class="hidden max-w-full max-h-full object-contain rounded-lg shadow-2xl" 
            alt="Preview"
        />
        
        <!-- Image Info -->
        <div id="lightbox-info" class="hidden absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent p-6 text-white">
            <h3 id="lightbox-title" class="text-xl font-semibold mb-2"></h3>
            <p id="lightbox-details" class="text-sm opacity-90"></p>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const lightbox = document.getElementById('image-lightbox');
    const lightboxImage = document.getElementById('lightbox-image');
    const lightboxLoader = document.getElementById('lightbox-loader');
    const lightboxInfo = document.getElementById('lightbox-info');
    const lightboxTitle = document.getElementById('lightbox-title');
    const lightboxDetails = document.getElementById('lightbox-details');
    const closeLightbox = document.getElementById('close-lightbox');
    
    // Function to open lightbox
    window.openImageLightbox = function(imageUrl, title, details) {
        lightbox.style.display = 'block';
        lightbox.classList.remove('hidden');
        lightboxLoader.classList.remove('hidden');
        lightboxImage.classList.add('hidden');
        lightboxInfo.classList.add('hidden');
        
        // Load image
        const img = new Image();
        img.onload = function() {
            lightboxImage.src = imageUrl;
            lightboxImage.classList.remove('hidden');
            lightboxLoader.classList.add('hidden');
            
            // Set info
            if (title || details) {
                lightboxTitle.textContent = title || '';
                lightboxDetails.textContent = details || '';
                lightboxInfo.classList.remove('hidden');
            }
        };
        img.onerror = function() {
            lightboxLoader.classList.add('hidden');
            lightboxImage.alt = 'Failed to load image';
            lightboxImage.classList.remove('hidden');
        };
        img.src = imageUrl;
        
        // Prevent body scroll
        document.body.style.overflow = 'hidden';
    };
    
    // Function to close lightbox
    function closeLightboxModal() {
        lightbox.style.display = 'none';
        lightbox.classList.add('hidden');
        lightboxImage.src = '';
        document.body.style.overflow = 'auto';
    }
    
    // Event listeners
    closeLightbox.addEventListener('click', closeLightboxModal);
    
    lightbox.addEventListener('click', function(e) {
        if (e.target === lightbox) {
            closeLightboxModal();
        }
    });
    
    // ESC key to close
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !lightbox.classList.contains('hidden')) {
            closeLightboxModal();
        }
    });
});
</script>

<style>
#image-lightbox {
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
}

#lightbox-image {
    transition: opacity 0.3s ease-in-out;
}

#lightbox-info {
    transition: opacity 0.3s ease-in-out;
}

.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>
