<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NewsController extends Controller
{
    // Helper function to transform news model to array
    private function transformNews($news)
    {
        return [
            'id' => $news->id,
            'title' => $news->title,
            'slug' => $news->slug,
            'excerpt' => $news->excerpt,
            'content' => $news->content,
            'featured_image' => $news->featured_image,
            'featured_image_url' => $news->featured_image_url,
            'category' => $news->category,
            'status' => $news->status,
            'published_at' => $news->published_at,
            'views_count' => $news->views_count,
            'tags' => $news->tags ?? [],
            'meta_title' => $news->meta_title,
            'meta_description' => $news->meta_description,
            'author' => [
                'id' => $news->author->id,
                'name' => $news->author->name,
            ],
        ];
    }

    public function index(Request $request)
    {
        $query = News::with('author')
            ->where('status', 'published')
            ->orderBy('published_at', 'desc');

        // Apply search filter
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('excerpt', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        // Apply category filter
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Apply sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'popular':
                    $query->orderBy('views_count', 'desc');
                    break;
                case 'oldest':
                    $query->orderBy('published_at', 'asc');
                    break;
                default:
                    $query->orderBy('published_at', 'desc');
                    break;
            }
        }

        $news = $query->paginate(9);
        
        // Transform news data
        $transformedNews = [
            'data' => $news->getCollection()->map(function($item) {
                return $this->transformNews($item);
            }),
            'current_page' => $news->currentPage(),
            'last_page' => $news->lastPage(),
            'per_page' => $news->perPage(),
            'total' => $news->total(),
        ];

        // Get featured news (latest 6 with high views)
        $featuredNews = News::with('author')
            ->where('status', 'published')
            ->orderBy('views_count', 'desc')
            ->take(6)
            ->get()
            ->map(function($item) {
                return $this->transformNews($item);
            });

        // Category mapping
        $categories = [
            'penghargaan' => 'Penghargaan',
            'kegiatan' => 'Kegiatan',
            'inovasi' => 'Inovasi',
            'kemitraan' => 'Kemitraan',
            'pelatihan' => 'Pelatihan',
            'milestone' => 'Milestone',
        ];

        return Inertia::render('News/Index', [
            'news' => $transformedNews,
            'featuredNews' => $featuredNews,
            'categories' => $categories,
            'filters' => [
                'search' => $request->search ?? '',
                'category' => $request->category ?? '',
                'sort' => $request->sort ?? 'latest',
            ],
        ]);
    }

    public function show($slug)
    {
        $news = News::with('author')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();

        // Increment views count
        $news->increment('views_count');

        // Get related news (same category, exclude current)
        $relatedNews = News::with('author')
            ->where('category', $news->category)
            ->where('id', '!=', $news->id)
            ->where('status', 'published')
            ->take(4)
            ->get()
            ->map(function($item) {
                return $this->transformNews($item);
            });

        // Get next and previous news
        $nextNews = News::where('published_at', '>', $news->published_at)
            ->where('status', 'published')
            ->orderBy('published_at', 'asc')
            ->first();

        $previousNews = News::where('published_at', '<', $news->published_at)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->first();

        // Category name mapping
        $categoryNames = [
            'penghargaan' => 'Penghargaan',
            'kegiatan' => 'Kegiatan',
            'inovasi' => 'Inovasi',
            'kemitraan' => 'Kemitraan',
            'pelatihan' => 'Pelatihan',
            'milestone' => 'Milestone',
        ];

        return Inertia::render('News/Show', [
            'news' => $this->transformNews($news),
            'relatedNews' => $relatedNews,
            'nextNews' => $nextNews ? $this->transformNews($nextNews) : null,
            'previousNews' => $previousNews ? $this->transformNews($previousNews) : null,
            'categoryName' => $categoryNames[$news->category] ?? ucfirst($news->category),
        ]);
    }

    public function category($category)
    {
        $query = News::with('author')
            ->where('category', $category)
            ->where('status', 'published')
            ->orderBy('published_at', 'desc');

        $news = $query->paginate(9);
        
        // Transform news data
        $transformedNews = [
            'data' => $news->getCollection()->map(function($item) {
                return $this->transformNews($item);
            }),
            'current_page' => $news->currentPage(),
            'last_page' => $news->lastPage(),
            'per_page' => $news->perPage(),
            'total' => $news->total(),
        ];

        // Category name mapping
        $categoryNames = [
            'penghargaan' => 'Penghargaan',
            'kegiatan' => 'Kegiatan',
            'inovasi' => 'Inovasi',
            'kemitraan' => 'Kemitraan',
            'pelatihan' => 'Pelatihan',
            'milestone' => 'Milestone',
        ];

        return Inertia::render('News/Category', [
            'news' => $transformedNews,
            'category' => $category,
            'categoryName' => $categoryNames[$category] ?? ucfirst($category),
        ]);
    }
}
