<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NewsController extends Controller
{
    /**
     * Display a listing of news
     */
    public function index(Request $request)
    {
        $query = News::published()->with('author');

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('content', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'LIKE', '%' . $request->search . '%');
            });
        }

        // Category filter
        if ($request->has('category') && $request->category) {
            $query->where('category', $request->category);
        }

        // Sort options
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'oldest':
                $query->orderBy('published_at', 'asc');
                break;
            case 'latest':
            default:
                $query->orderBy('published_at', 'desc');
                break;
        }

        $news = $query->paginate(12)->withQueryString();

        // Get categories for filter
        $categories = News::getCategoryOptions();

        // Get featured news (latest 3 with images)
        $featuredNews = News::published()
            ->whereNotNull('featured_image')
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        return Inertia::render('News/Index', [
            'news' => $news,
            'categories' => $categories,
            'featuredNews' => $featuredNews,
            'filters' => [
                'search' => $request->search,
                'category' => $request->category,
                'sort' => $sortBy,
            ]
        ]);
    }

    /**
     * Display the specified news
     */
    public function show(News $news)
    {
        // Check if news is published
        if (!$news->isPublished()) {
            abort(404);
        }

        // Increment views count
        $news->increment('views_count');

        // Load relationships
        $news->load('author');

        // Get related news (same category, excluding current)
        $relatedNews = News::published()
            ->where('category', $news->category)
            ->where('id', '!=', $news->id)
            ->orderBy('published_at', 'desc')
            ->take(4)
            ->get();

        // Get next and previous news
        $nextNews = News::published()
            ->where('published_at', '>', $news->published_at)
            ->orderBy('published_at', 'asc')
            ->first();

        $previousNews = News::published()
            ->where('published_at', '<', $news->published_at)
            ->orderBy('published_at', 'desc')
            ->first();

        return Inertia::render('News/Show', [
            'news' => $news,
            'relatedNews' => $relatedNews,
            'nextNews' => $nextNews,
            'previousNews' => $previousNews,
            'categoryName' => News::getCategoryOptions()[$news->category] ?? $news->category,
        ]);
    }

    /**
     * Display news by category
     */
    public function category(Request $request, string $category)
    {
        // Validate category exists
        $categories = array_keys(News::getCategoryOptions());
        if (!in_array($category, $categories)) {
            abort(404);
        }

        $query = News::published()
            ->where('category', $category)
            ->with('author');

        // Search within category
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('content', 'LIKE', '%' . $request->search . '%')
                  ->orWhere('excerpt', 'LIKE', '%' . $request->search . '%');
            });
        }

        // Sort options
        $sortBy = $request->get('sort', 'latest');
        switch ($sortBy) {
            case 'popular':
                $query->orderBy('views_count', 'desc');
                break;
            case 'oldest':
                $query->orderBy('published_at', 'asc');
                break;
            case 'latest':
            default:
                $query->orderBy('published_at', 'desc');
                break;
        }

        $news = $query->paginate(12)->withQueryString();

        return Inertia::render('News/Category', [
            'news' => $news,
            'category' => $category,
            'categoryName' => News::getCategoryOptions()[$category],
            'allCategories' => News::getCategoryOptions(),
            'filters' => [
                'search' => $request->search,
                'sort' => $sortBy,
            ]
        ]);
    }

    /**
     * Search news via AJAX for autocomplete
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2',
        ]);

        $news = News::published()
            ->where(function ($query) use ($request) {
                $query->where('title', 'LIKE', '%' . $request->q . '%')
                      ->orWhere('excerpt', 'LIKE', '%' . $request->q . '%');
            })
            ->select('id', 'title', 'slug', 'featured_image', 'published_at')
            ->orderBy('published_at', 'desc')
            ->take(5)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'title' => $item->title,
                    'slug' => $item->slug,
                    'featured_image_url' => $item->featured_image_url,
                    'published_at' => $item->published_at->format('d M Y'),
                    'url' => route('news.show', $item->slug)
                ];
            });

        return response()->json($news);
    }
}
