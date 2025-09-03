<div class="space-y-6">
    {{-- Main Image Display --}}
    <div class="flex justify-center bg-gray-50 dark:bg-gray-900 rounded-lg p-4">
        <div class="relative group">
            <img 
                src="{{ $imageUrl }}" 
                alt="{{ $title }}" 
                class="max-w-full max-h-96 object-contain rounded-lg shadow-lg cursor-pointer hover:shadow-xl transition-shadow duration-300"
                style="max-height: 500px;"
                onclick="openImageLightbox('{{ $imageUrl }}', '{{ $title }}', 'File: {{ basename($featured_image) }}')"
            />
            <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-10 transition-all duration-300 rounded-lg flex items-center justify-center">
                <div class="bg-white bg-opacity-90 rounded-full p-3 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                    <svg class="w-6 h-6 text-gray-800" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Image Information --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Basic Info --}}
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                Informasi Gambar
            </h3>
            
            <div class="space-y-2">
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Nama File:</span>
                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                        {{ basename($featured_image) }}
                    </span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Format:</span>
                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                        {{ strtoupper(pathinfo($featured_image, PATHINFO_EXTENSION)) }}
                    </span>
                </div>
                
                <div class="flex justify-between">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Path:</span>
                    <span class="text-sm font-mono text-gray-700 dark:text-gray-300 break-all">
                        {{ $featured_image }}
                    </span>
                </div>
            </div>
        </div>
        
        {{-- Stats --}}
        <div class="space-y-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                Statistik
            </h3>
            
            <div class="space-y-2">
                @if($stats && isset($stats['current_size']))
                    <div class="flex justify-between">
                        <span class="text-sm text-gray-600 dark:text-gray-400">Ukuran Saat Ini:</span>
                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ number_format($stats['current_size'] / 1024, 2) }} KB
                        </span>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Include Lightbox Component --}}
@include('filament.components.image-lightbox')

<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(function() {
            // You can add a toast notification here if needed
            alert('URL berhasil disalin ke clipboard!');
        }, function(err) {
            console.error('Gagal menyalin URL: ', err);
            alert('Gagal menyalin URL');
        });
    }
</script>
