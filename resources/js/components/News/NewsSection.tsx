import React from 'react';
import { Link } from '@inertiajs/react';
import NewsCard from '@/components/News/NewsCard';

interface NewsItem {
    id: number;
    title: string;
    slug: string;
    excerpt: string;
    content: string;
    featured_image: string | null;
    featured_image_url: string | null;
    category: string;
    published_at: string;
    views_count: number;
    author: {
        id: number;
        name: string;
    };
}

interface NewsSectionProps {
    latestNews: NewsItem[];
    title?: string;
    subtitle?: string;
}

export default function NewsSection({ latestNews, title = "Berita Terbaru", subtitle = "Ikuti perkembangan terkini dan informasi terbaru dari Bank Sampah Cipta Muri" }: NewsSectionProps) {
    if (!latestNews || latestNews.length === 0) {
        return null;
    }

    return (
        <section className="py-20 bg-gradient-to-br from-gray-50 to-white relative overflow-hidden">
            {/* Background Decoration */}
            <div className="absolute top-0 left-0 w-full h-full opacity-5">
                <svg className="w-full h-full" viewBox="0 0 100 100" fill="none">
                    <defs>
                        <pattern id="news-pattern" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">
                            <circle cx="2" cy="2" r="1" fill="currentColor" />
                            <circle cx="12" cy="12" r="1" fill="currentColor" />
                        </pattern>
                    </defs>
                    <rect width="100%" height="100%" fill="url(#news-pattern)" />
                </svg>
            </div>

            <div className="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                {/* Section Header */}
                <div className="text-center mb-16">
                    <div className="inline-flex items-center gap-2 bg-green-100 text-green-800 px-4 py-2 rounded-full font-semibold mb-6">
                        <span className="text-xl">
                            <svg className="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                            </svg>
                        </span>
                        <span>Pusat Informasi</span>
                    </div>
                    
                    <h2 className="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                        {title}
                    </h2>
                    
                    <p className="text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed">
                        {subtitle}
                    </p>
                </div>

                {/* Featured News Grid */}
                <div className="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
                    {/* Main Featured Article */}
                    {latestNews[0] && (
                        <div className="lg:col-span-2">
                            <Link href={route('news.show', latestNews[0].slug)} className="group block">
                                <article className="relative bg-white rounded-2xl shadow-xl overflow-hidden h-96 hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2">
                                    {latestNews[0].featured_image_url ? (
                                        <div className="absolute inset-0">
                                            <img
                                                src={latestNews[0].featured_image_url}
                                                alt={latestNews[0].title}
                                                className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700"
                                            />
                                            <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent" />
                                        </div>
                                    ) : (
                                        <div className="absolute inset-0 bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center">
                                            <svg className="w-24 h-24 text-white opacity-30" fill="currentColor" viewBox="0 0 20 20">
                                                <path fillRule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clipRule="evenodd" />
                                            </svg>
                                            <div className="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent" />
                                        </div>
                                    )}
                                    
                                    <div className="absolute bottom-0 left-0 right-0 p-8">
                                        <div className="flex items-center gap-2 mb-4">
                                            <span className="bg-red-500 text-white px-3 py-1 rounded-full text-sm font-bold">
                                                TRENDING
                                            </span>
                                            <span className="text-white/80 text-sm">
                                                {new Date(latestNews[0].published_at).toLocaleDateString('id-ID', {
                                                    day: 'numeric',
                                                    month: 'long',
                                                    year: 'numeric'
                                                })}
                                            </span>
                                        </div>
                                        
                                        <h3 className="text-2xl md:text-3xl font-bold text-white mb-3 line-clamp-2 group-hover:text-yellow-300 transition-colors">
                                            {latestNews[0].title}
                                        </h3>
                                        
                                        <p className="text-white/90 line-clamp-2 mb-4">
                                            {latestNews[0].excerpt}
                                        </p>

                                        <div className="flex items-center justify-between">
                                            <div className="flex items-center gap-2 text-white/80 text-sm">
                                                <div className="w-6 h-6 bg-white/20 rounded-full flex items-center justify-center">
                                                    {latestNews[0].author.name.charAt(0).toUpperCase()}
                                                </div>
                                                <span>{latestNews[0].author.name}</span>
                                            </div>
                                            <div className="flex items-center gap-1 text-white/80 text-sm">
                                                <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                                <span>{latestNews[0].views_count}</span>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </Link>
                        </div>
                    )}

                    {/* Side Articles */}
                    <div className="space-y-6">
                        {latestNews.slice(1, 3).map((item) => (
                            <NewsCard 
                                key={item.id} 
                                news={item} 
                                size="small" 
                                showExcerpt={false}
                                showViews={true}
                            />
                        ))}
                    </div>
                </div>

                {/* More Articles Grid */}
                {latestNews.length > 3 && (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
                        {latestNews.slice(3, 6).map((item) => (
                            <NewsCard 
                                key={item.id} 
                                news={item} 
                                size="medium"
                            />
                        ))}
                    </div>
                )}

                {/* Call to Action */}
                <div className="text-center">
                    <Link
                        href={route('news.index')}
                        className="inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-green-500 to-green-600 text-white font-bold text-lg rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-1"
                    >
                        <span>📚 Lihat Semua Berita</span>
                        <svg className="w-5 h-5 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </Link>
                </div>
            </div>
        </section>
    );
}
