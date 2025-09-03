<?php

namespace App\Traits;

use JoshEmbling\ImageOptimizer\Facades\ImageOptimizer;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait HasOptimizedImages
{
    /**
     * Boot the trait
     */
    protected static function bootHasOptimizedImages()
    {
        static::creating(function ($model) {
            if (request()->hasFile('featured_image')) {
                $model->optimizeAndStoreImage('featured_image');
            }
        });

        static::updating(function ($model) {
            if (request()->hasFile('featured_image')) {
                // Delete old image if exists
                if ($model->getOriginal('featured_image')) {
                    Storage::disk('public')->delete($model->getOriginal('featured_image'));
                }
                $model->optimizeAndStoreImage('featured_image');
            }
        });

        static::deleting(function ($model) {
            if ($model->featured_image) {
                Storage::disk('public')->delete($model->featured_image);
            }
        });
    }

    /**
     * Optimize and store image
     */
    protected function optimizeAndStoreImage($attribute)
    {
        $file = request()->file($attribute);
        
        if ($file instanceof UploadedFile) {
            // Generate unique filename
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = 'news/' . $filename;
            
            // Store original file temporarily
            $tempPath = $file->store('temp');
            $fullTempPath = Storage::disk('public')->path($tempPath);
            
            // Optimize image
            ImageOptimizer::optimize($fullTempPath);
            
            // Move optimized image to final location
            Storage::disk('public')->move($tempPath, $path);
            
            $this->attributes[$attribute] = $path;
        }
    }

    /**
     * Get full URL for featured image
     */
    public function getFeaturedImageUrlAttribute()
    {
        return $this->featured_image ? Storage::disk('public')->url($this->featured_image) : null;
    }

    /**
     * Get optimized image with different sizes
     */
    public function getOptimizedImageUrl($width = null, $height = null)
    {
        if (!$this->featured_image) {
            return null;
        }

        $path = Storage::disk('public')->path($this->featured_image);
        
        if ($width || $height) {
            $info = pathinfo($this->featured_image);
            $resizedName = $info['filename'] . "_{$width}x{$height}." . $info['extension'];
            $resizedPath = 'news/thumbnails/' . $resizedName;
            
            if (!Storage::disk('public')->exists($resizedPath)) {
                // Create thumbnail if not exists
                $image = ImageOptimizer::make($path);
                
                if ($width && $height) {
                    $image->fit($width, $height);
                } elseif ($width) {
                    $image->widen($width);
                } elseif ($height) {
                    $image->heighten($height);
                }
                
                $image->save(Storage::disk('public')->path($resizedPath));
            }
            
            return Storage::disk('public')->url($resizedPath);
        }
        
        return Storage::disk('public')->url($this->featured_image);
    }
}
