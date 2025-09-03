<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content', 
        'featured_image',
        'status',
        'published_at',
        'author_id',
        'category',
        'tags',
        'meta_title',
        'meta_description',
        'views_count'
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'tags' => 'array',
        'views_count' => 'integer',
    ];

    protected $dates = ['deleted_at'];

    // Status constants
    const STATUS_DRAFT = 'draft';
    const STATUS_PUBLISHED = 'published';
    const STATUS_SCHEDULED = 'scheduled';

    // Relationship with User (Author)
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', self::STATUS_PUBLISHED)
                    ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Mutators & Accessors
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function getExcerptAttribute($value)
    {
        return $value ?: Str::limit(strip_tags($this->content), 150);
    }

    // Helper methods
    public function isPublished(): bool
    {
        return $this->status === self::STATUS_PUBLISHED && $this->published_at <= now();
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function getReadingTime(): string
    {
        $words = str_word_count(strip_tags($this->content));
        $minutes = ceil($words / 200); // Average reading speed
        return $minutes . ' min read';
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public static function getStatusOptions(): array
    {
        return [
            self::STATUS_DRAFT => 'Draft',
            self::STATUS_PUBLISHED => 'Published',
            self::STATUS_SCHEDULED => 'Scheduled',
        ];
    }

    public static function getCategoryOptions(): array
    {
        return [
            'general' => 'General',
            'announcement' => 'Announcement',
            'event' => 'Event',
            'program' => 'Program',
            'achievement' => 'Achievement',
            'education' => 'Education',
        ];
    }

    // Image helper methods
    public function getFeaturedImageUrlAttribute()
    {
        if (!$this->featured_image) {
            return null;
        }
        
        // Use relative URL to avoid CORS issues
        return url('storage/' . $this->featured_image);
    }

    public function getThumbnailUrl($width = 300, $height = 200)
    {
        return $this->featured_image_url;
    }

    public function getCardImageUrl()
    {
        return $this->featured_image_url;
    }

    public function getHeroImageUrl()
    {
        return $this->featured_image_url;
    }
}
