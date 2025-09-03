<?php

namespace App\Services;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ImageOptimizationService
{
    /**
     * Manual optimize using Intervention Image (fallback for existing images)
     */
    public static function optimize(string $filePath, string $disk = 'public'): bool
    {
        try {
            if (!Storage::disk($disk)->exists($filePath)) {
                Log::warning("Image file not found: {$filePath}");
                return false;
            }

            $fullPath = Storage::disk($disk)->path($filePath);
            $fileSize = filesize($fullPath);
            
            // Check if file size exceeds maximum
            $maxSize = config('image-optimizer.max_file_size', 5242880); // 5MB default
            if ($fileSize > $maxSize) {
                Log::info("Skipping optimization for large file: {$filePath} ({$fileSize} bytes)");
                return false;
            }

            // Check mime type
            $mimeType = Storage::disk($disk)->mimeType($filePath);
            $supportedTypes = config('image-optimizer.supported_mime_types', [
                'image/jpeg', 'image/jpg', 'image/png', 'image/webp'
            ]);

            if (!in_array($mimeType, $supportedTypes)) {
                Log::info("Unsupported mime type for optimization: {$mimeType}");
                return false;
            }

            // Create backup if configured
            if (config('image-optimizer.backup_originals', false)) {
                $backupPath = str_replace('.', '_original.', $filePath);
                Storage::disk($disk)->copy($filePath, $backupPath);
            }

            // Perform optimization using Intervention Image
            $image = Image::make($fullPath);
            
            // Resize if image is too large
            if ($image->width() > 1200 || $image->height() > 800) {
                $image->resize(1200, 800, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
            }
            
            // Save with quality optimization
            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            switch ($extension) {
                case 'jpg':
                case 'jpeg':
                    $image->save($fullPath, 85, 'jpg');
                    break;
                case 'png':
                    $image->save($fullPath, 9, 'png');
                    break;
                case 'webp':
                    $image->save($fullPath, 85, 'webp');
                    break;
                default:
                    $image->save($fullPath, 85);
            }
            
            $newFileSize = filesize($fullPath);
            $savedBytes = $fileSize - $newFileSize;
            $savedPercentage = round(($savedBytes / $fileSize) * 100, 2);
            
            Log::info("Image optimized: {$filePath}. Saved {$savedBytes} bytes ({$savedPercentage}%)");
            
            return true;

        } catch (\Exception $e) {
            Log::error("Image optimization failed for {$filePath}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Optimize multiple images
     */
    public static function optimizeBatch(array $filePaths, string $disk = 'public'): array
    {
        $results = [
            'success' => 0,
            'failed' => 0,
            'skipped' => 0,
            'details' => []
        ];

        foreach ($filePaths as $filePath) {
            $result = self::optimize($filePath, $disk);
            
            if ($result) {
                $results['success']++;
                $results['details'][] = ['path' => $filePath, 'status' => 'optimized'];
            } else {
                $results['failed']++;
                $results['details'][] = ['path' => $filePath, 'status' => 'failed'];
            }
        }

        return $results;
    }

    /**
     * Check if auto optimization is enabled
     */
    public static function isAutoOptimizationEnabled(): bool
    {
        return config('image-optimizer.auto_optimize', true);
    }

    /**
     * Get optimization statistics for a file
     */
    public static function getOptimizationStats(string $filePath, string $disk = 'public'): ?array
    {
        if (!Storage::disk($disk)->exists($filePath)) {
            return null;
        }

        $fullPath = Storage::disk($disk)->path($filePath);
        $currentSize = filesize($fullPath);
        
        // Check if backup exists
        $backupPath = str_replace('.', '_original.', $filePath);
        if (Storage::disk($disk)->exists($backupPath)) {
            $originalSize = filesize(Storage::disk($disk)->path($backupPath));
            $savedBytes = $originalSize - $currentSize;
            $savedPercentage = round(($savedBytes / $originalSize) * 100, 2);
            
            return [
                'original_size' => $originalSize,
                'current_size' => $currentSize,
                'saved_bytes' => $savedBytes,
                'saved_percentage' => $savedPercentage,
                'has_backup' => true
            ];
        }

        return [
            'current_size' => $currentSize,
            'has_backup' => false
        ];
    }
}
