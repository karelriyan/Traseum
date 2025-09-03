import NewsCard from '@/components/News/NewsCard';
import MainLayout from '@/layouts/MainLayout';
import { formatNumber, formatRelativeTime, stripHtmlTags, truncateText } from '@/Utils/dateHelpers';
import { Head, Link, router } from '@inertiajs/react';
import { useState } from 'react';

// Simple SVG Icons
const MagnifyingGlassIcon = ({ className }: { className?: string }) => (
    <svg className={className} fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="m21 21-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
    </svg>
);

const EyeIcon = ({ className }: { className?: string }) => (
    <svg className={className} fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path
            strokeLinecap="round"
            strokeLinejoin="round"
            strokeWidth={2}
            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
        />
    </svg>
);

const FilterIcon = ({ className }: { className?: string }) => (
    <svg className={className} fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path
            strokeLinecap="round"
            strokeLinejoin="round"
            strokeWidth={2}
            d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"
        />
    </svg>
);

interface NewsItem {
    id: number;
    title: string;
    slug: string;
    excerpt: string;
    content: string;
    featured_image: string | null;
    featured_image_url: string | null;
    category: string;
    status: string;
    published_at: string;
    views_count: number;
    author: {
        id: number;
        name: string;
    };
}

interface NewsIndexProps {
    news: {
        data: NewsItem[];
        current_page: number;
        last_page: number;
        per_page: number;
        total: number;
        links: Array<{
            url: string | null;
            label: string;
            active: boolean;
        }>;
    };
    categories: Record<string, string>;
    featuredNews: NewsItem[];
    filters: {
        search?: string;
        category?: string;
        sort: string;
    };
}

export default function NewsIndex({ news, categories, featuredNews, filters }: NewsIndexProps) {
    const [searchQuery, setSearchQuery] = useState(filters.search || '');
    const [selectedCategory, setSelectedCategory] = useState(filters.category || '');
    const [sortBy, setSortBy] = useState(filters.sort || 'latest');
    const [isLoading, setIsLoading] = useState(false);

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        setIsLoading(true);
        router.get(
            route('news.index'),
            {
                search: searchQuery,
                category: selectedCategory,
                sort: sortBy,
            },
            {
                preserveState: true,
                replace: true,
                onFinish: () => setIsLoading(false),
            },
        );
    };

    const handleFilterChange = (newFilters: Partial<typeof filters>) => {
        setIsLoading(true);
        router.get(
            route('news.index'),
            {
                search: searchQuery,
                category: selectedCategory,
                sort: sortBy,
                ...newFilters,
            },
            {
                preserveState: true,
                replace: true,
                onFinish: () => setIsLoading(false),
            },
        );
    };

    const FeaturedNewsCard = ({ item }: { item: NewsItem }) => (
        <Link href={route('news.show', item.slug)} className="group block">
            <article className="relative h-80 transform overflow-hidden rounded-2xl bg-white shadow-xl transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl">
                {item.featured_image_url ? (
                    <div className="absolute inset-0">
                        <img
                            src={item.featured_image_url}
                            alt={item.title}
                            className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
                            loading="lazy"
                        />
                        <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent" />
                    </div>
                ) : (
                    <div className="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-green-400 via-green-500 to-green-600">
                        <svg className="h-20 w-20 text-white opacity-30" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                fillRule="evenodd"
                                d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                clipRule="evenodd"
                            />
                        </svg>
                        <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent" />
                    </div>
                )}

                <div className="absolute bottom-0 left-0 right-0 p-6 text-white">
                    <div className="mb-3 flex items-center gap-2 text-sm">
                        <span className="rounded-full bg-yellow-500 px-3 py-1 text-xs font-bold text-black">⭐ UNGGULAN</span>
                        <span className="text-gray-200">{formatRelativeTime(item.published_at)}</span>
                    </div>

                    <h3 className="mb-2 line-clamp-2 text-xl font-bold transition-colors group-hover:text-yellow-300">{item.title}</h3>

                    <p className="mb-3 line-clamp-2 text-sm text-gray-200">{item.excerpt || truncateText(stripHtmlTags(item.content || ''), 100)}</p>

                    <div className="flex items-center justify-between text-xs text-gray-300">
                        <div className="flex items-center gap-2">
                            <div className="flex h-6 w-6 items-center justify-center rounded-full bg-white/20">
                                {(item.author?.name?.charAt(0) || '?').toUpperCase()}
                            </div>
                            <span>{item.author?.name ?? 'Tanpa Penulis'}</span>
                        </div>
                        <div className="flex items-center gap-1">
                            <EyeIcon className="h-4 w-4" />
                            <span>{formatNumber(item.views_count)}</span>
                        </div>
                    </div>
                </div>
            </article>
        </Link>
    );

    const Pagination = () => {
        if (news.last_page <= 1) return null;

        return (
            <div className="mt-12 flex justify-center">
                <nav className="flex items-center space-x-2">
                    {news.links.map((link, index) => {
                        if (!link.url) {
                            return <span key={index} className="px-4 py-2 text-sm text-gray-400" dangerouslySetInnerHTML={{ __html: link.label }} />;
                        }

                        return (
                            <Link
                                key={index}
                                href={link.url}
                                className={`rounded-lg px-4 py-2 text-sm font-medium transition-all duration-300 ${
                                    link.active
                                        ? 'bg-green-500 text-white shadow-lg'
                                        : 'border border-gray-200 bg-white text-gray-700 shadow-sm hover:bg-green-50 hover:text-green-600'
                                }`}
                                dangerouslySetInnerHTML={{ __html: link.label }}
                            />
                        );
                    })}
                </nav>
            </div>
        );
    };

    return (
        <MainLayout>
            <Head title="Berita & Artikel - Bank Sampah Cipta Muri" />

            {/* Hero Section with Featured News */}
            <section className="relative min-h-[40vh] overflow-hidden bg-gradient-to-br from-green-600 via-green-700 to-emerald-800 text-white">
                <div className="absolute inset-0 bg-black bg-opacity-10"></div>
                <div className="absolute inset-0">
                    <div className="absolute inset-0 bg-gradient-to-r from-green-900/50 to-transparent"></div>
                    <svg className="absolute bottom-0 left-0 h-32 w-full text-white opacity-10" viewBox="0 0 1200 120" fill="currentColor">
                        <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z"></path>
                    </svg>
                </div>

                <div className="relative mx-auto max-w-7xl px-4 py-16 sm:px-6 lg:px-8 lg:py-20">
                    <div className="mb-16 text-center">
                        <h1 className="mb-6 text-4xl font-bold lg:text-6xl">
                            <span className="bg-gradient-to-r from-yellow-300 via-yellow-100 to-white bg-clip-text text-transparent">
                                📰 Berita & Artikel
                            </span>
                        </h1>
                        <p className="mx-auto max-w-3xl text-xl leading-relaxed text-green-100 lg:text-2xl">
                            Informasi terkini seputar program, kegiatan, dan pencapaian Bank Sampah Cipta Muri
                        </p>
                    </div>

                    {/* Featured News Section */}
                    {featuredNews.length > 0 && (
                        <div className="mb-8">
                            <h2 className="mb-8 text-center text-2xl font-bold text-white">🌟 Berita Unggulan</h2>
                            <div className="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                                {featuredNews.slice(0, 3).map((item) => (
                                    <FeaturedNewsCard key={item.id} item={item} />
                                ))}
                            </div>
                        </div>
                    )}
                </div>
            </section>

            {/* Main Content */}
            <section className="bg-gray-50 py-16">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    {/* Search and Filters */}
                    <div className="mb-12 rounded-2xl bg-white p-8 shadow-xl">
                        <form onSubmit={handleSearch} className="space-y-6">
                            <div className="flex flex-col gap-4 sm:flex-row">
                                <div className="flex-1">
                                    <div className="relative">
                                        <MagnifyingGlassIcon className="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 transform text-gray-400" />
                                        <input
                                            type="text"
                                            value={searchQuery}
                                            onChange={(e) => setSearchQuery(e.target.value)}
                                            placeholder="🔍 Cari berita, artikel, atau topik tertentu..."
                                            className="w-full rounded-xl border border-gray-200 py-4 pl-12 pr-4 text-lg transition-all duration-300 focus:border-green-500 focus:ring-2 focus:ring-green-500"
                                        />
                                    </div>
                                </div>
                                <button
                                    type="submit"
                                    disabled={isLoading}
                                    className="transform rounded-xl bg-gradient-to-r from-green-500 to-green-600 px-8 py-4 text-lg font-semibold text-white shadow-lg transition-all duration-300 hover:-translate-y-1 hover:from-green-600 hover:to-green-700 hover:shadow-xl disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    {isLoading ? '🔄 Mencari...' : '🔍 Cari'}
                                </button>
                            </div>

                            {/* Filters */}
                            <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label className="mb-3 flex items-center gap-2 text-sm font-semibold text-gray-700">
                                        <FilterIcon className="h-4 w-4" />
                                        📂 Kategori
                                    </label>
                                    <select
                                        value={selectedCategory}
                                        onChange={(e) => {
                                            setSelectedCategory(e.target.value);
                                            handleFilterChange({ category: e.target.value });
                                        }}
                                        className="w-full rounded-xl border border-gray-200 px-4 py-3 text-lg transition-all duration-300 focus:border-green-500 focus:ring-2 focus:ring-green-500"
                                    >
                                        <option value="">🌟 Semua Kategori</option>
                                        {Object.entries(categories).map(([key, label]) => (
                                            <option key={key} value={key}>
                                                {label}
                                            </option>
                                        ))}
                                    </select>
                                </div>

                                <div>
                                    <label className="mb-3 flex items-center gap-2 text-sm font-semibold text-gray-700">
                                        <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth={2}
                                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"
                                            />
                                        </svg>
                                        📊 Urutkan
                                    </label>
                                    <select
                                        value={sortBy}
                                        onChange={(e) => {
                                            setSortBy(e.target.value);
                                            handleFilterChange({ sort: e.target.value });
                                        }}
                                        className="w-full rounded-xl border border-gray-200 px-4 py-3 text-lg transition-all duration-300 focus:border-green-500 focus:ring-2 focus:ring-green-500"
                                    >
                                        <option value="latest">🆕 Terbaru</option>
                                        <option value="popular">🔥 Terpopuler</option>
                                        <option value="oldest">📅 Terlama</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>

                    {/* News Results */}
                    <div className="mb-8">
                        <div className="mb-8 flex flex-wrap items-center justify-between gap-4">
                            <h2 className="flex items-center gap-3 text-3xl font-bold text-gray-800">
                                <span className="rounded-lg bg-green-100 p-2">📖</span>
                                Semua Berita
                            </h2>
                            <div className="rounded-full bg-white px-6 py-3 shadow-md">
                                <span className="font-medium text-gray-600">{formatNumber(news.total)} artikel ditemukan</span>
                            </div>
                        </div>

                        {/* Loading State */}
                        {isLoading && (
                            <div className="flex items-center justify-center py-20">
                                <div className="h-12 w-12 animate-spin rounded-full border-b-2 border-green-500"></div>
                                <span className="ml-4 text-lg text-gray-600">Memuat berita...</span>
                            </div>
                        )}

                        {!isLoading && news.data.length > 0 ? (
                            <>
                                <div className="mb-12 grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                                    {news.data.map((item) => (
                                        <NewsCard key={item.id} news={item} size="medium" />
                                    ))}
                                </div>

                                <Pagination />
                            </>
                        ) : !isLoading ? (
                            <div className="rounded-2xl bg-white py-20 text-center shadow-lg">
                                <div className="mb-6 text-6xl">🔍</div>
                                <h3 className="mb-4 text-2xl font-bold text-gray-800">Oops! Tidak Ada Berita Ditemukan</h3>
                                <p className="mx-auto mb-8 max-w-md text-lg text-gray-600">
                                    Coba ubah kata kunci pencarian atau pilih kategori yang berbeda untuk menemukan artikel yang menarik.
                                </p>
                                <button
                                    onClick={() => {
                                        setSearchQuery('');
                                        setSelectedCategory('');
                                        setSortBy('latest');
                                        router.get(route('news.index'));
                                    }}
                                    className="inline-flex items-center gap-2 rounded-xl bg-green-500 px-6 py-3 font-semibold text-white transition-colors duration-300 hover:bg-green-600"
                                >
                                    🔄 Reset Filter
                                </button>
                            </div>
                        ) : null}
                    </div>
                </div>
            </section>
        </MainLayout>
    );
}
