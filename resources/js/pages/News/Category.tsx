import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import MainLayout from '@/layouts/MainLayout';
import { formatRelativeTime, formatNumber, stripHtmlTags, truncateText } from '@/Utils/dateHelpers';

// Simple SVG Icons
const CalendarIcon = ({ className }: { className?: string }) => (
    <svg className={className} fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
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

const MagnifyingGlassIcon = ({ className }: { className?: string }) => (
    <svg className={className} fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="m21 21-6-6m2-5a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z" />
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

interface NewsCategoryProps {
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
    category: string;
    categoryName: string;
    allCategories: Record<string, string>;
    filters: {
        search?: string;
        sort: string;
    };
}

export default function NewsCategory({ news, category, categoryName, allCategories, filters }: NewsCategoryProps) {
    const [searchQuery, setSearchQuery] = useState(filters.search || '');
    const [sortBy, setSortBy] = useState(filters.sort || 'latest');

    const handleSearch = (e: React.FormEvent) => {
        e.preventDefault();
        router.get(route('news.category', category), {
            search: searchQuery,
            sort: sortBy,
        }, {
            preserveState: true,
            replace: true,
        });
    };

    const handleFilterChange = (newFilters: Partial<typeof filters>) => {
        router.get(route('news.category', category), {
            search: searchQuery,
            sort: sortBy,
            ...newFilters,
        }, {
            preserveState: true,
            replace: true,
        });
    };

    const NewsCard = ({ item }: { item: NewsItem }) => (
        <article className="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
            {item.featured_image_url && (
                <div className="aspect-video overflow-hidden">
                    <img
                        src={item.featured_image_url}
                        alt={item.title}
                        className="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                    />
                </div>
            )}
            
            <div className="p-6">
                <div className="flex items-center gap-2 text-sm text-gray-500 mb-3">
                    <span className="flex items-center gap-1">
                        <CalendarIcon className="w-4 h-4" />
                        {formatRelativeTime(item.published_at)}
                    </span>
                    <span className="flex items-center gap-1">
                        <EyeIcon className="w-4 h-4" />
                        {formatNumber(item.views_count)}
                    </span>
                </div>
                
                <h3 className="font-bold text-lg mb-2 line-clamp-2 hover:text-green-600 transition-colors">
                    <Link href={route('news.show', item.slug)}>
                        {item.title}
                    </Link>
                </h3>
                
                <p className="text-gray-600 mb-4 line-clamp-3">
                    {item.excerpt || truncateText(stripHtmlTags(item.content || ''), 150)}
                </p>
                
                <div className="flex items-center justify-between">
                    <div className="flex items-center gap-2 text-sm text-gray-500">
                        <UserIcon className="w-4 h-4" />
                        <span>{item.author.name}</span>
                    </div>
                    
                    <Link
                        href={route('news.show', item.slug)}
                        className="text-green-600 hover:text-green-800 font-medium text-sm"
                    >
                        Baca Selengkapnya →
                    </Link>
                </div>
            </div>
        </article>
    );

    const Pagination = () => {
        if (news.last_page <= 1) return null;

        return (
            <div className="flex justify-center mt-12">
                <nav className="flex space-x-2">
                    {news.links.map((link, index) => {
                        if (!link.url) return null;
                        
                        return (
                            <Link
                                key={index}
                                href={link.url}
                                className={`px-3 py-2 text-sm rounded-md transition-colors ${
                                    link.active
                                        ? 'bg-green-600 text-white'
                                        : 'bg-gray-200 text-gray-700 hover:bg-gray-300'
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
            <Head title={`Berita ${categoryName} - Bank Sampah Cipta Muri`} />

            <div className="min-h-screen bg-gray-50">
                {/* Breadcrumb */}
                <div className="bg-white border-b">
                    <div className="container mx-auto px-4 py-4">
                        <nav className="flex items-center space-x-2 text-sm text-gray-600">
                            <Link href={route('home')} className="hover:text-green-600">Home</Link>
                            <span>/</span>
                            <Link href={route('news.index')} className="hover:text-green-600">Berita</Link>
                            <span>/</span>
                            <span className="text-gray-900">{categoryName}</span>
                        </nav>
                    </div>
                </div>

                <div className="container mx-auto px-4 py-8">
                    {/* Header */}
                    <div className="text-center mb-12">
                        <h1 className="text-4xl font-bold text-gray-900 mb-4">
                            Berita {categoryName}
                        </h1>
                        <p className="text-lg text-gray-600">
                            Informasi terkini seputar {categoryName.toLowerCase()} Bank Sampah Cipta Muri
                        </p>
                    </div>

                    {/* Categories Navigation */}
                    <div className="mb-8">
                        <div className="bg-white rounded-lg shadow-md p-4">
                            <h3 className="text-lg font-semibold mb-4">Kategori Lainnya</h3>
                            <div className="flex flex-wrap gap-2">
                                {Object.entries(allCategories).map(([value, label]) => (
                                    <Link
                                        key={value}
                                        href={route('news.category', value)}
                                        className={`px-4 py-2 rounded-full text-sm transition-colors ${
                                            value === category
                                                ? 'bg-green-600 text-white'
                                                : 'bg-gray-100 text-gray-700 hover:bg-gray-200'
                                        }`}
                                    >
                                        {label}
                                    </Link>
                                ))}
                            </div>
                        </div>
                    </div>

                    {/* Search and Filters */}
                    <div className="bg-white rounded-lg shadow-md p-6 mb-8">
                        <form onSubmit={handleSearch} className="flex flex-col md:flex-row gap-4">
                            <div className="flex-1">
                                <div className="relative">
                                    <MagnifyingGlassIcon className="absolute left-3 top-1/2 transform -translate-y-1/2 h-5 w-5 text-gray-400" />
                                    <input
                                        type="text"
                                        placeholder={`Cari dalam ${categoryName}...`}
                                        value={searchQuery}
                                        onChange={(e) => setSearchQuery(e.target.value)}
                                        className="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                                    />
                                </div>
                            </div>
                            
                            <select
                                value={sortBy}
                                onChange={(e) => {
                                    setSortBy(e.target.value);
                                    handleFilterChange({ sort: e.target.value });
                                }}
                                className="px-4 py-2 border border-gray-300 rounded-md focus:ring-green-500 focus:border-green-500"
                            >
                                <option value="latest">Terbaru</option>
                                <option value="popular">Terpopuler</option>
                                <option value="oldest">Terlama</option>
                            </select>
                            
                            <button
                                type="submit"
                                className="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors"
                            >
                                Cari
                            </button>
                        </form>
                    </div>

                    {/* News Grid */}
                    {news.data.length > 0 ? (
                        <>
                            <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                                {news.data.map((item) => (
                                    <NewsCard key={item.id} item={item} />
                                ))}
                            </div>
                            
                            <Pagination />
                        </>
                    ) : (
                        <div className="text-center py-12">
                            <div className="text-gray-400 mb-4">
                                <MagnifyingGlassIcon className="w-16 h-16 mx-auto" />
                            </div>
                            <h3 className="text-lg font-medium text-gray-900 mb-2">
                                Tidak Ada Berita Ditemukan
                            </h3>
                            <p className="text-gray-500 mb-4">
                                Tidak ada berita dalam kategori "{categoryName}" yang sesuai dengan pencarian Anda.
                            </p>
                            <Link
                                href={route('news.index')}
                                className="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 transition-colors"
                            >
                                Lihat Semua Berita
                            </Link>
                        </div>
                    )}
                </div>
            </div>
        </MainLayout>
    );
}
