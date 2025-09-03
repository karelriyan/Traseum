import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import MainLayout from '@/layouts/MainLayout';
import NewsCard from '@/components/News/NewsCard';
import { formatDate, formatRelativeTime, formatNumber, stripHtmlTags, truncateText } from '@/Utils/dateHelpers';

// Simple SVG Icons
const MagnifyingGlassIcon = ({ className }: { className?: string }) => (
    <svg className={className} fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="m21 21-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
    </svg>
);

const EyeIcon = ({ className }: { className?: string }) => (
    <svg className={className} fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
    </svg>
);

const UserIcon = ({ className }: { className?: string }) => (
    <svg className={className} fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
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

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        router.get(route('news.index'), {
            search: searchQuery,
            category: selectedCategory,
            sort: sortBy,
        }, {
            preserveState: true,
            replace: true,
        });
    };

    const handleFilterChange = (newFilters: Partial<typeof filters>) => {
        router.get(route('news.index'), {
            search: searchQuery,
            category: selectedCategory,
            sort: sortBy,
            ...newFilters,
        }, {
            preserveState: true,
            replace: true,
        });
    };

    const FeaturedNewsCard = ({ item }: { item: NewsItem }) => (
        <Link href={route('news.show', item.slug)} className="block group">
            <article className="relative bg-white rounded-2xl shadow-xl overflow-hidden h-80 hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                {item.featured_image_url ? (
                    <div className="absolute inset-0">
                        <img
                            src={item.featured_image_url}
                            alt={item.title}
                            className="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700"
                        />
                        <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent" />
                    </div>
                ) : (
                    <div className="absolute inset-0 bg-gradient-to-br from-green-400 via-green-500 to-green-600 flex items-center justify-center">
                        <svg className="w-20 h-20 text-white opacity-30" fill="currentColor" viewBox="0 0 20 20">
                            <path fillRule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clipRule="evenodd" />
                        </svg>
                        <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent" />
                    </div>
                )}
                
                <div className="absolute bottom-0 left-0 right-0 p-6 text-white">
                    <div className="flex items-center gap-2 text-sm mb-3">
                        <span className="bg-yellow-500 text-black px-3 py-1 rounded-full text-xs font-bold">
                            ⭐ UNGGULAN
                        </span>
                        <span className="text-gray-200">{formatRelativeTime(item.published_at)}</span>
                    </div>
                    
                    <h3 className="font-bold text-xl mb-2 line-clamp-2 group-hover:text-yellow-300 transition-colors">
                        {item.title}
                    </h3>
                    
                    <p className="text-gray-200 text-sm line-clamp-2 mb-3">
                        {item.excerpt || truncateText(stripHtmlTags(item.content || ''), 100)}
                    </p>

                    <div className="flex items-center justify-between text-xs text-gray-300">
                        <div className="flex items-center gap-2">
                            <div className="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center">
                                {(item.author?.name?.charAt(0) || '?').toUpperCase()}
                            </div>
                            <span>{item.author?.name ?? 'Tanpa Penulis'}</span>
                        </div>
                        <div className="flex items-center gap-1">
                            <EyeIcon className="w-4 h-4" />
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
            <div className="flex justify-center mt-12">
                <nav className="flex items-center space-x-2">
                    {news.links.map((link, index) => {
                        if (!link.url) {
                            return (
                                <span
                                    key={index}
                                    className="px-4 py-2 text-sm text-gray-400"
                                    dangerouslySetInnerHTML={{ __html: link.label }}
                                />
                            );
                        }
                        
                        return (
                            <Link
                                key={index}
                                href={link.url}
                                className={`px-4 py-2 text-sm rounded-lg font-medium transition-all duration-300 ${
                                    link.active
                                        ? 'bg-green-500 text-white shadow-lg'
                                        : 'bg-white text-gray-700 hover:bg-green-50 hover:text-green-600 border border-gray-200 shadow-sm'
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
            <section className="relative bg-gradient-to-br from-green-600 via-green-700 to-emerald-800 text-white overflow-hidden">
                <div className="absolute inset-0 bg-black bg-opacity-10"></div>
                <div className="absolute inset-0">
                    <div className="absolute inset-0 bg-gradient-to-r from-green-900/50 to-transparent"></div>
                    <svg className="absolute bottom-0 left-0 w-full h-32 text-white opacity-10" viewBox="0 0 1200 120" fill="currentColor">
                        <path d="M985.66,92.83C906.67,72,823.78,31,743.84,14.19c-82.26-17.34-168.06-16.33-250.45.39-57.84,11.73-114,31.07-172,41.86A600.21,600.21,0,0,1,0,27.35V120H1200V95.8C1132.19,118.92,1055.71,111.31,985.66,92.83Z"></path>
                    </svg>
                </div>
                
                <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
                    <div className="text-center mb-16">
                        <div className="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm rounded-full px-4 py-2 mb-6">
                            <span className="text-2xl">📰</span>
                            <span className="font-semibold">Pusat Informasi</span>
                        </div>
                        <h1 className="text-5xl md:text-7xl font-bold mb-6 bg-gradient-to-r from-white via-green-100 to-emerald-100 bg-clip-text text-transparent">
                            Berita & Artikel
                        </h1>
                        <p className="text-xl md:text-2xl text-green-100 max-w-3xl mx-auto leading-relaxed">
                            Ikuti perkembangan terbaru program lingkungan, inovasi daur ulang, dan gerakan keberlanjutan bersama Bank Sampah Cipta Muri
                        </p>
                    </div>

                    {/* Featured News Carousel */}
                    {featuredNews.length > 0 && (
                        <div className="grid md:grid-cols-3 gap-8 mt-16">
                            {featuredNews.slice(0, 3).map((item) => (
                                <FeaturedNewsCard key={item.id} item={item} />
                            ))}
                        </div>
                    )}
                </div>
            </section>

            {/* Main Content */}
            <section className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 bg-gray-50 min-h-screen">
                {/* Search and Filter Section */}
                <div className="bg-white rounded-2xl shadow-xl p-8 mb-16 border-t-4 border-green-500">
                    <div className="flex items-center gap-3 mb-6">
                        <div className="bg-green-100 p-2 rounded-lg">
                            <MagnifyingGlassIcon className="w-6 h-6 text-green-600" />
                        </div>
                        <h2 className="text-2xl font-bold text-gray-800">Cari & Filter Berita</h2>
                    </div>

                    <form onSubmit={handleSearch} className="space-y-6">
                        {/* Search Bar */}
                        <div className="flex flex-col md:flex-row gap-4">
                            <div className="relative flex-1">
                                <MagnifyingGlassIcon className="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                                <input
                                    type="text"
                                    placeholder="Cari berita, artikel, atau topik menarik..."
                                    value={searchQuery}
                                    onChange={(e) => setSearchQuery(e.target.value)}
                                    className="w-full pl-12 pr-4 py-4 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300 text-lg"
                                />
                            </div>
                            <button
                                type="submit"
                                className="px-8 py-4 bg-gradient-to-r from-green-500 to-green-600 text-white font-semibold rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1 text-lg"
                            >
                                🔍 Cari
                            </button>
                        </div>

                        {/* Filters */}
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label className="block text-sm font-semibold text-gray-700 mb-3">📂 Kategori</label>
                                <select
                                    value={selectedCategory}
                                    onChange={(e) => {
                                        setSelectedCategory(e.target.value);
                                        handleFilterChange({ category: e.target.value });
                                    }}
                                    className="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300 text-lg"
                                >
                                    <option value="">🌟 Semua Kategori</option>
                                    {Object.entries(categories).map(([key, label]) => (
                                        <option key={key} value={key}>{label}</option>
                                    ))}
                                </select>
                            </div>
                            
                            <div>
                                <label className="block text-sm font-semibold text-gray-700 mb-3">📊 Urutkan</label>
                                <select
                                    value={sortBy}
                                    onChange={(e) => {
                                        setSortBy(e.target.value);
                                        handleFilterChange({ sort: e.target.value });
                                    }}
                                    className="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-green-500 focus:border-green-500 transition-all duration-300 text-lg"
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
                    <div className="flex items-center justify-between mb-8">
                        <h2 className="text-3xl font-bold text-gray-800 flex items-center gap-3">
                            <span className="bg-green-100 p-2 rounded-lg">📖</span>
                            Semua Berita
                        </h2>
                        <div className="bg-white rounded-full px-4 py-2 shadow-md">
                            <span className="text-gray-600 font-medium">
                                {formatNumber(news.total)} artikel ditemukan
                            </span>
                        </div>
                    </div>

                    {news.data.length > 0 ? (
                        <>
                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                                {news.data.map((item) => (
                                    <NewsCard key={item.id} news={item} size="medium" />
                                ))}
                            </div>
                            
                            <Pagination />
                        </>
                    ) : (
                        <div className="text-center py-20 bg-white rounded-2xl shadow-lg">
                            <div className="text-6xl mb-6">🔍</div>
                            <h3 className="text-2xl font-bold text-gray-800 mb-4">
                                Oops! Tidak Ada Berita Ditemukan
                            </h3>
                            <p className="text-lg text-gray-600 max-w-md mx-auto mb-8">
                                Coba ubah kata kunci pencarian atau pilih kategori yang berbeda untuk menemukan artikel yang menarik.
                            </p>
                            <button
                                onClick={() => {
                                    setSearchQuery('');
                                    setSelectedCategory('');
                                    setSortBy('latest');
                                    router.get(route('news.index'));
                                }}
                                className="inline-flex items-center gap-2 px-6 py-3 bg-green-500 text-white font-semibold rounded-xl hover:bg-green-600 transition-colors duration-300"
                            >
                                🔄 Reset Filter
                            </button>
                        </div>
                    )}
                </div>
            </section>
        </MainLayout>
    );
}

