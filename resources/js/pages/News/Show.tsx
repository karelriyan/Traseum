import { Head, Link } from '@inertiajs/react';
import MainLayout from '@/layouts/MainLayout';
import NewsCard from '@/components/News/NewsCard';
import { formatDate, formatRelativeTime, formatNumber } from '@/Utils/dateHelpers';

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

const ArrowLeftIcon = ({ className }: { className?: string }) => (
    <svg className={className} fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m-7 7h18" />
    </svg>
);

const ShareIcon = ({ className }: { className?: string }) => (
    <svg className={className} fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z" />
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
    tags: string[];
    meta_title?: string;
    meta_description?: string;
    author: {
        id: number;
        name: string;
    };
}

interface NewsShowProps {
    news: NewsItem;
    relatedNews: NewsItem[];
    nextNews: NewsItem | null;
    previousNews: NewsItem | null;
    categoryName: string;
}

export default function NewsShow({ news, relatedNews, nextNews, previousNews, categoryName }: NewsShowProps) {
    const shareUrl = typeof window !== 'undefined' ? window.location.href : '';
    
    const handleShare = () => {
        if (navigator.share) {
            navigator.share({
                title: news.title,
                text: news.excerpt,
                url: shareUrl,
            });
        } else {
            // Fallback: copy to clipboard
            navigator.clipboard.writeText(shareUrl);
            alert('Link berhasil disalin ke clipboard!');
        }
    };

    return (
        <MainLayout>
            <Head title={news.meta_title || news.title}>
                <meta name="description" content={news.meta_description || news.excerpt} />
                <meta property="og:title" content={news.title} />
                <meta property="og:description" content={news.excerpt} />
                {news.featured_image_url && (
                    <meta property="og:image" content={news.featured_image_url} />
                )}
                <meta property="og:url" content={shareUrl} />
                <meta property="og:type" content="article" />
            </Head>

            {/* Hero Section with Article Image */}
            <section className="relative bg-gradient-to-r from-gray-900 to-gray-800 text-white overflow-hidden">
                {news.featured_image_url && (
                    <div className="absolute inset-0">
                        <img
                            src={news.featured_image_url}
                            alt={news.title}
                            className="w-full h-full object-cover opacity-30"
                        />
                        <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-black/20" />
                    </div>
                )}
                
                <div className="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
                    {/* Back Button */}
                    <Link
                        href={route('news.index')}
                        className="inline-flex items-center gap-2 text-white/80 hover:text-white mb-8 transition-colors bg-black/20 backdrop-blur-sm px-4 py-2 rounded-full"
                    >
                        <ArrowLeftIcon className="w-5 h-5" />
                        Kembali ke Berita
                    </Link>

                    {/* Article Meta */}
                    <div className="flex items-center gap-4 text-sm text-white/80 mb-6">
                        <span className="bg-green-500 text-white px-4 py-2 rounded-full font-bold">
                            📰 {categoryName}
                        </span>
                        <div className="flex items-center gap-2">
                            <CalendarIcon className="w-4 h-4" />
                            <span>{formatDate(news.published_at)}</span>
                        </div>
                        <div className="flex items-center gap-2">
                            <EyeIcon className="w-4 h-4" />
                            <span>{formatNumber(news.views_count)} views</span>
                        </div>
                    </div>

                    <h1 className="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                        {news.title}
                    </h1>

                    {news.excerpt && (
                        <p className="text-xl md:text-2xl text-white/90 leading-relaxed max-w-3xl">
                            {news.excerpt}
                        </p>
                    )}

                    {/* Author & Share */}
                    <div className="flex items-center justify-between mt-8 pt-6 border-t border-white/20">
                        <div className="flex items-center gap-3">
                            <div className="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center text-white font-bold">
                                {news.author.name.charAt(0).toUpperCase()}
                            </div>
                            <div>
                                <div className="text-white font-semibold">{news.author.name}</div>
                                <div className="text-white/60 text-sm">Penulis</div>
                            </div>
                        </div>

                        <button
                            onClick={handleShare}
                            className="flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white hover:bg-white/20 transition-all duration-300 px-6 py-3 rounded-full"
                        >
                            <ShareIcon className="w-5 h-5" />
                            <span className="font-medium">Bagikan</span>
                        </button>
                    </div>
                </div>
            </section>

            {/* Article Content */}
            <section className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-16 bg-white">
                <article className="prose prose-lg prose-green max-w-none">
                    <div 
                        dangerouslySetInnerHTML={{ __html: news.content }}
                        className="text-gray-800 leading-relaxed [&>h1]:text-3xl [&>h1]:font-bold [&>h1]:text-gray-900 [&>h1]:mb-6 [&>h1]:mt-8 
                                 [&>h2]:text-2xl [&>h2]:font-bold [&>h2]:text-gray-900 [&>h2]:mb-4 [&>h2]:mt-8 
                                 [&>h3]:text-xl [&>h3]:font-bold [&>h3]:text-gray-900 [&>h3]:mb-4 [&>h3]:mt-6
                                 [&>p]:text-gray-700 [&>p]:leading-relaxed [&>p]:mb-4
                                 [&>ul]:mb-4 [&>ul]:pl-6 [&>li]:mb-2 [&>li]:text-gray-700
                                 [&>blockquote]:border-l-4 [&>blockquote]:border-green-500 [&>blockquote]:pl-4 [&>blockquote]:italic [&>blockquote]:text-gray-600 [&>blockquote]:bg-green-50 [&>blockquote]:py-2 [&>blockquote]:rounded-r
                                 [&>img]:rounded-lg [&>img]:shadow-lg [&>img]:my-6"
                    />
                </article>

                {/* Tags */}
                {news.tags && news.tags.length > 0 && (
                    <div className="mt-12 pt-8 border-t border-gray-200">
                        <h3 className="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                            🏷️ Tags
                        </h3>
                        <div className="flex flex-wrap gap-3">
                            {news.tags.map((tag, index) => (
                                <span
                                    key={index}
                                    className="bg-gradient-to-r from-green-100 to-green-50 text-green-800 px-4 py-2 rounded-full text-sm font-medium hover:from-green-200 hover:to-green-100 transition-all duration-300 cursor-pointer"
                                >
                                    #{tag}
                                </span>
                            ))}
                        </div>
                    </div>
                )}
            </section>

            {/* Navigation */}
            {(previousNews || nextNews) && (
                <section className="bg-gray-50 py-12">
                    <div className="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {previousNews && (
                                <Link
                                    href={route('news.show', previousNews.slug)}
                                    className="group bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-green-200"
                                >
                                    <div className="flex items-center gap-2 text-green-600 font-medium mb-3">
                                        <ArrowLeftIcon className="w-4 h-4" />
                                        <span>Artikel Sebelumnya</span>
                                    </div>
                                    <h3 className="font-bold text-gray-900 group-hover:text-green-700 transition-colors line-clamp-2">
                                        {previousNews.title}
                                    </h3>
                                </Link>
                            )}
                            
                            {nextNews && (
                                <Link
                                    href={route('news.show', nextNews.slug)}
                                    className="group bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-green-200 text-right"
                                >
                                    <div className="flex items-center justify-end gap-2 text-green-600 font-medium mb-3">
                                        <span>Artikel Selanjutnya</span>
                                        <svg className="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </div>
                                    <h3 className="font-bold text-gray-900 group-hover:text-green-700 transition-colors line-clamp-2">
                                        {nextNews.title}
                                    </h3>
                                </Link>
                            )}
                        </div>
                    </div>
                </section>
            )}

            {/* Related News */}
            {relatedNews.length > 0 && (
                <section className="bg-white py-16">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="text-center mb-12">
                            <div className="inline-flex items-center gap-2 bg-green-100 text-green-800 px-4 py-2 rounded-full font-semibold mb-4">
                                🔗 Artikel Terkait
                            </div>
                            <h2 className="text-3xl md:text-4xl font-bold text-gray-900">
                                Baca Juga Artikel Menarik Lainnya
                            </h2>
                            <p className="text-lg text-gray-600 mt-4">
                                Temukan artikel serupa yang mungkin menarik bagi Anda
                            </p>
                        </div>
                        
                        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                            {relatedNews.map((item) => (
                                <NewsCard 
                                    key={item.id} 
                                    news={item} 
                                    size="small" 
                                    showExcerpt={false}
                                />
                            ))}
                        </div>
                    </div>
                </section>
            )}
        </MainLayout>
    );
}
