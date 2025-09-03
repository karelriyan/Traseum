<?php

namespace App\Observers;

use App\Models\News;
use Illuminate\Support\Facades\Storage;
use JoshEmbling\ImageOptimizer\Facades\ImageOptimizer;

class NewsObserver
{
    /**
     * Handle the News "created" event.
     */
    public function created(News $news): void
    {
        $this->optimizeImage($news);
    }

    /**
     * Handle the News "updated" event.
     */
    public function updated(News $news): void
    {
        // Check if featured_image was changed
        if ($news->wasChanged('featured_image')) {
            // Delete old image
            $originalImage = $news->getOriginal('featured_image');
            if ($originalImage && Storage::disk('public')->exists($originalImage)) {
                Storage::disk('public')->delete($originalImage);
                
                // Also delete thumbnails
                $this->deleteThumbnails($originalImage);
            }

            // Optimize new image
            $this->optimizeImage($news);
        }
    }

    /**
     * Handle the News "deleted" event.
     */
    public function deleted(News $news): void
    {
        if ($news->featured_image && Storage::disk('public')->exists($news->featured_image)) {
            Storage::disk('public')->delete($news->featured_image);
            $this->deleteThumbnails($news->featured_image);
        }
    }

    /**
     * Handle the News "restored" event.
     */
    public function restored(News $news): void
    {
        //
    }

    /**
     * Handle the News "force deleted" event.
     */
    public function forceDeleted(News $news): void
    {
        if ($news->featured_image && Storage::disk('public')->exists($news->featured_image)) {
            Storage::disk('public')->delete($news->featured_image);
            $this->deleteThumbnails($news->featured_image);
        }
    }

    /**
     * Optimize image after upload
     */
    private function optimizeImage(News $news): void
    {
        if ($news->featured_image && Storage::disk('public')->exists($news->featured_image)) {
            $fullPath = Storage::disk('public')->path($news->featured_image);
            
            try {
                // Optimize the image
                ImageOptimizer::optimize($fullPath);
                
                // Generate different sized thumbnails
                $this->generateThumbnails($news->featured_image);
                
            } catch (\Exception $e) {
                // Log error but don't break the flow
                logger()->error('Image optimization failed: ' . $e->getMessage());
            }
        }
    }

    /**
     * Generate thumbnails for different sizes
     */
    private function generateThumbnails(string $imagePath): void
    {
        $thumbnailSizes = [
            ['width' => 300, 'height' => 200],  // Card thumbnail
            ['width' => 400, 'height' => 250],  // Medium thumbnail
            ['width' => 800, 'height' => 500],  // Large thumbnail
        ];

        $pathInfo = pathinfo($imagePath);
        $sourcePath = Storage::disk('public')->path($imagePath);

        foreach ($thumbnailSizes as $size) {
            $thumbnailName = $pathInfo['filename'] . "_{$size['width']}x{$size['height']}." . $pathInfo['extension'];
            $thumbnailPath = 'news/thumbnails/' . $thumbnailName;
            $fullThumbnailPath = Storage::disk('public')->path($thumbnailPath);

            try {
                // Create directory if not exists
                $thumbnailDir = dirname($fullThumbnailPath);
                if (!is_dir($thumbnailDir)) {
                    mkdir($thumbnailDir, 0755, true);
                }

                // Create thumbnail using Intervention Image
                $image = \Intervention\Image\Facades\Image::make($sourcePath);
                $image->fit($size['width'], $size['height'], function ($constraint) {
                    $constraint->upsize();
                });
                $image->save($fullThumbnailPath, 85); // 85% quality

                // Optimize thumbnail
                ImageOptimizer::optimize($fullThumbnailPath);

            } catch (\Exception $e) {
                logger()->error("Thumbnail generation failed for size {$size['width']}x{$size['height']}: " . $e->getMessage());
            }
        }
    }

    /**
     * Delete all thumbnails for an image
     */
    private function deleteThumbnails(string $imagePath): void
    {
        $pathInfo = pathinfo($imagePath);
        $thumbnailPattern = 'news/thumbnails/' . $pathInfo['filename'] . '_*.' . $pathInfo['extension'];
        
        $thumbnails = Storage::disk('public')->glob($thumbnailPattern);
        foreach ($thumbnails as $thumbnail) {
            Storage::disk('public')->delete($thumbnail);
        }
    }
}
