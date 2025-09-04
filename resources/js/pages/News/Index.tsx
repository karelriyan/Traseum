import NewsCard from '@/components/News/NewsCard';
import NewsLayout from '@/layouts/NewsLayout';
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
                        <span className="rounded-full bg-yellow-500 px-3 py-1 text-xs font-bold text-black">UNGGULAN</span>
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
        <NewsLayout>
            <Head title="Berita & Artikel - Bank Sampah Cipta Muri" />

            {/* Hero Section with Featured News */}
            <section className="relative overflow-hidden bg-white">
                {/* Background Pattern */}
                <div className="absolute inset-0 opacity-[0.02]">
                    <svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg">
                        <g fill="none" fillRule="evenodd">
                            <g fill="#000000" fillOpacity="1">
                                <circle cx="7" cy="7" r="1" />
                                <circle cx="27" cy="7" r="1" />
                                <circle cx="47" cy="7" r="1" />
                                <circle cx="7" cy="27" r="1" />
                                <circle cx="27" cy="27" r="1" />
                                <circle cx="47" cy="27" r="1" />
                                <circle cx="7" cy="47" r="1" />
                                <circle cx="27" cy="47" r="1" />
                                <circle cx="47" cy="47" r="1" />
                            </g>
                        </g>
                    </svg>
                </div>

                <div className="relative mx-auto max-w-7xl px-4 py-20 sm:px-6 lg:px-8 lg:py-24">
                    <div className="text-center">
                        <div className="mb-8 inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-100 px-6 py-3 font-medium text-slate-700">
                            <svg className="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path
                                    strokeLinecap="round"
                                    strokeLinejoin="round"
                                    strokeWidth={1.5}
                                    d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"
                                />
                            </svg>
                            <span className="text-sm font-semibold uppercase tracking-wide">Pusat Informasi</span>
                        </div>

                        <h1 className="mb-8 text-5xl font-bold tracking-tight text-slate-900 md:text-6xl lg:text-7xl">
                            Berita & <span className="text-green-600">Publikasi</span>
                        </h1>

                        <div className="mx-auto mb-8 h-1 w-32 rounded-full bg-gradient-to-r from-green-500 to-green-600"></div>

                        <p className="mx-auto max-w-3xl text-xl font-light leading-relaxed text-slate-600 lg:text-2xl">
                            Informasi terkini seputar program, kegiatan, dan pencapaian Bank Sampah Cipta Muri dalam membangun masa depan yang
                            berkelanjutan
                        </p>
                    </div>
                </div>
            </section>

            {/* Main Content */}
            <section className="bg-slate-50 py-20">
                <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                    {/* Featured News Section */}
                    {featuredNews.length > 0 && (
                        <div className="mb-16">
                            <div className="mb-12 text-center">
                                <h2 className="mb-4 text-3xl font-bold text-slate-900">Berita Unggulan</h2>
                                <div className="mx-auto h-1 w-20 rounded-full bg-green-500"></div>
                            </div>
                            <div className="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                                {featuredNews.slice(0, 3).map((item) => (
                                    <FeaturedNewsCard key={item.id} item={item} />
                                ))}
                            </div>
                        </div>
                    )}

                    {/* Search and Filters */}
                    <div className="mb-16 rounded-3xl border border-slate-200 bg-white p-8 shadow-lg">
                        <div className="mb-8 text-center">
                            <h3 className="mb-2 text-2xl font-bold text-slate-900">Cari Berita</h3>
                            <p className="text-slate-600">Temukan informasi yang Anda butuhkan dengan mudah</p>
                        </div>

                        <form onSubmit={handleSearch} className="space-y-8">
                            <div className="flex flex-col gap-4 sm:flex-row">
                                <div className="flex-1">
                                    <div className="relative">
                                        <MagnifyingGlassIcon className="absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 transform text-slate-400" />
                                        <input
                                            type="text"
                                            value={searchQuery}
                                            onChange={(e) => setSearchQuery(e.target.value)}
                                            placeholder="Cari berita, artikel, atau topik tertentu..."
                                            className="w-full rounded-2xl border border-slate-200 bg-slate-50 py-4 pl-12 pr-4 text-lg transition-all duration-300 focus:border-green-500 focus:bg-white focus:ring-2 focus:ring-green-500 focus:ring-opacity-20"
                                        />
                                    </div>
                                </div>
                                <button
                                    type="submit"
                                    disabled={isLoading}
                                    className="rounded-2xl bg-slate-900 px-8 py-4 font-semibold text-white shadow-lg transition-all duration-300 hover:bg-slate-800 hover:shadow-xl disabled:cursor-not-allowed disabled:opacity-50"
                                >
                                    {isLoading ? 'Mencari...' : 'Cari Berita'}
                                </button>
                            </div>

                            {/* Filters */}
                            <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div>
                                    <label className="mb-3 flex items-center gap-2 text-sm font-semibold text-slate-700">
                                        <FilterIcon className="h-4 w-4" />
                                        Kategori
                                    </label>
                                    <select
                                        value={selectedCategory}
                                        onChange={(e) => {
                                            setSelectedCategory(e.target.value);
                                            handleFilterChange({ category: e.target.value });
                                        }}
                                        className="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-lg transition-all duration-300 focus:border-green-500 focus:bg-white focus:ring-2 focus:ring-green-500 focus:ring-opacity-20"
                                    >
                                        <option value="">Semua Kategori</option>
                                        {Object.entries(categories).map(([key, label]) => (
                                            <option key={key} value={key}>
                                                {label}
                                            </option>
                                        ))}
                                    </select>
                                </div>

                                <div>
                                    <label className="mb-3 flex items-center gap-2 text-sm font-semibold text-slate-700">
                                        <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth={2}
                                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"
                                            />
                                        </svg>
                                        Urutkan
                                    </label>
                                    <select
                                        value={sortBy}
                                        onChange={(e) => {
                                            setSortBy(e.target.value);
                                            handleFilterChange({ sort: e.target.value });
                                        }}
                                        className="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-lg transition-all duration-300 focus:border-green-500 focus:bg-white focus:ring-2 focus:ring-green-500 focus:ring-opacity-20"
                                    >
                                        <option value="latest">Terbaru</option>
                                        <option value="popular">Terpopuler</option>
                                        <option value="oldest">Terlama</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>

                    {/* News Results */}
                    <div className="mb-16">
                        <div className="mb-12 flex flex-wrap items-center justify-between gap-4">
                            <div>
                                <h2 className="mb-2 text-3xl font-bold text-slate-900">Semua Berita</h2>
                                <div className="h-1 w-16 rounded-full bg-green-500"></div>
                            </div>
                            <div className="rounded-2xl border border-slate-200 bg-white px-6 py-3 shadow-sm">
                                <p className="text-sm text-slate-600">
                                    <span className="font-semibold text-slate-900">{formatNumber(news.total)}</span> berita ditemukan
                                </p>
                            </div>
                        </div>

                        {/* Loading State */}
                        {isLoading && (
                            <div className="flex items-center justify-center py-20">
                                <div className="h-12 w-12 animate-spin rounded-full border-4 border-slate-200 border-t-green-500"></div>
                                <span className="ml-4 text-lg text-slate-600">Memuat berita...</span>
                            </div>
                        )}

                        {!isLoading && news.data.length > 0 ? (
                            <>
                                <div className="mb-16 grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                                    {news.data.map((item) => (
                                        <NewsCard key={item.id} news={item} size="small" showExcerpt={true} showAuthor={false} showViews={true} />
                                    ))}
                                </div>

                                <Pagination />
                            </>
                        ) : !isLoading ? (
                            <div className="rounded-3xl border border-slate-200 bg-white py-20 text-center shadow-lg">
                                <div className="mb-8">
                                    <svg className="mx-auto h-20 w-20 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth={1.5}
                                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"
                                        />
                                    </svg>
                                </div>
                                <h3 className="mb-4 text-2xl font-bold text-slate-900">Tidak Ada Berita Ditemukan</h3>
                                <p className="mx-auto mb-8 max-w-md text-lg leading-relaxed text-slate-600">
                                    Coba ubah kata kunci pencarian atau pilih kategori yang berbeda untuk menemukan artikel yang menarik.
                                </p>
                                <button
                                    onClick={() => {
                                        setSearchQuery('');
                                        setSelectedCategory('');
                                        setSortBy('latest');
                                        router.get(route('news.index'));
                                    }}
                                    className="inline-flex items-center gap-2 rounded-2xl bg-slate-900 px-8 py-4 font-semibold text-white shadow-lg transition-all duration-300 hover:bg-slate-800 hover:shadow-xl"
                                >
                                    <span>Reset Filter</span>
                                    <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth={2}
                                            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"
                                        />
                                    </svg>
                                </button>
                            </div>
                        ) : null}
                    </div>
                </div>
            </section>
        </NewsLayout>
    );
}
