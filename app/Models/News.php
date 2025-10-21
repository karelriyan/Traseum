<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class News extends Model
{
    use HasFactory, SoftDeletes, HasUlids, LogsActivity;

    protected $fillable = [
        'name',
        'text',
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

    protected $appends = [
        'featured_image_url'
    ];

    protected $dates = ['deleted_at'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName('news')
            ->logAll()
            ->setDescriptionForEvent(fn(string $eventName) => "Berita has been {$eventName}");
    }

    // Auto-generate fields
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($news) {
            // Auto-set author to current logged in user
            if (empty($news->author_id)) {
                $news->author_id = auth()->id();
            }

            // Auto-generate unique slug if not provided
            if (empty($news->slug)) {
                $slug = Str::slug($news->title);
                $originalSlug = $slug;
                $counter = 1;

                // Check if slug exists
                while (static::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $counter;
                    $counter++;
                }

                $news->slug = $slug;
            }

            // Auto-generate meta_title if not provided
            if (empty($news->meta_title)) {
                $news->meta_title = Str::limit($news->title, 60);
            }

            // Auto-generate meta_description if not provided
            if (empty($news->meta_description)) {
                $news->meta_description = Str::limit($news->excerpt, 160);
            }
        });

        static::updating(function ($news) {
            // Auto-generate unique slug if not provided or title changed
            if (empty($news->slug) || $news->isDirty('title')) {
                $slug = Str::slug($news->title);
                $originalSlug = $slug;
                $counter = 1;

                // Check if slug exists (excluding current record)
                while (static::where('slug', $slug)->where('id', '!=', $news->id)->exists()) {
                    $slug = $originalSlug . '-' . $counter;
                    $counter++;
                }

                $news->slug = $slug;
            }

            // Auto-generate meta_title if not provided
            if (empty($news->meta_title)) {
                $news->meta_title = Str::limit($news->title, 60);
            }

            // Auto-generate meta_description if not provided
            if (empty($news->meta_description)) {
                $news->meta_description = Str::limit($news->excerpt, 160);
            }
        });
    }

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
