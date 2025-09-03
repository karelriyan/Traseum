import { Link } from '@inertiajs/react';

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

interface NewsCardProps {
    news: NewsItem;
    size?: 'small' | 'medium' | 'large';
    showExcerpt?: boolean;
    showAuthor?: boolean;
    showViews?: boolean;
}

export default function NewsCard({ 
    news, 
    size = 'medium', 
    showExcerpt = true, 
    showAuthor = true,
    showViews = true 
}: NewsCardProps) {
    const formatRelativeTime = (dateString: string): string => {
        const date = new Date(dateString);
        const now = new Date();
        const diffInSeconds = Math.floor((now.getTime() - date.getTime()) / 1000);

        const intervals = [
            { label: 'tahun', seconds: 31536000 },
            { label: 'bulan', seconds: 2592000 },
            { label: 'minggu', seconds: 604800 },
            { label: 'hari', seconds: 86400 },
            { label: 'jam', seconds: 3600 },
            { label: 'menit', seconds: 60 }
        ];

        for (const interval of intervals) {
            const count = Math.floor(diffInSeconds / interval.seconds);
            if (count >= 1) {
                return `${count} ${interval.label} yang lalu`;
            }
        }

        return 'baru saja';
    };

    const stripHtmlTags = (html: string): string => {
        return html.replace(/<[^>]*>/g, '');
    };

    const truncateText = (text: string, maxLength: number): string => {
        if (text.length <= maxLength) return text;
        return text.substring(0, maxLength).trim() + '...';
    };

    const sizeClasses = {
        small: 'max-w-xs',
        medium: 'max-w-sm',
        large: 'max-w-md'
    };

    const titleSizeClasses = {
        small: 'text-base',
        medium: 'text-lg',
        large: 'text-xl'
    };

    return (
        <article className={`group bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 border border-gray-100 hover:border-green-200 ${sizeClasses[size]}`}>
            {news.featured_image_url ? (
                <div className="aspect-video overflow-hidden bg-gradient-to-br from-gray-100 to-gray-200">
                    <img
                        src={news.featured_image_url}
                        alt={news.title}
                        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                        loading="lazy"
                    />
                </div>
            ) : (
                <div className="aspect-video bg-gradient-to-br from-green-50 to-green-100 flex items-center justify-center">
                    <svg className="w-16 h-16 text-green-300" fill="currentColor" viewBox="0 0 20 20">
                        <path fillRule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clipRule="evenodd" />
                    </svg>
                </div>
            )}
            
            <div className="p-5">
                <div className="flex items-center gap-3 text-xs mb-3">
                    <span className="bg-gradient-to-r from-green-500 to-green-600 text-white px-3 py-1 rounded-full text-xs font-semibold shadow-sm">
                        🗞️ Berita
                    </span>
                    <div className="flex items-center gap-2 text-gray-500">
                        <span className="flex items-center gap-1">
                            ⏰ {formatRelativeTime(news.published_at)}
                        </span>
                        {showViews && (
                            <span className="flex items-center gap-1">
                                👁️ {news.views_count}
                            </span>
                        )}
                    </div>
                </div>
                
                <h3 className={`font-bold mb-3 line-clamp-2 text-gray-800 group-hover:text-green-700 transition-colors duration-300 ${titleSizeClasses[size]} leading-tight`}>
                    <Link href={route('news.show', news.slug)} className="hover:underline decoration-green-500 decoration-2 underline-offset-2">
                        {news.title}
                    </Link>
                </h3>
                
                {showExcerpt && (
                    <p className="text-gray-600 text-sm mb-4 line-clamp-3 leading-relaxed">
                        {news.excerpt || truncateText(stripHtmlTags(news.content || ''), 100)}
                    </p>
                )}
                
                <div className="flex items-center justify-between pt-2 border-t border-gray-100">
                    {showAuthor && (
                        <div className="flex items-center gap-2">
                            <div className="w-6 h-6 bg-gradient-to-r from-green-400 to-green-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                {news.author.name.charAt(0).toUpperCase()}
                            </div>
                            <span className="text-xs text-gray-500 font-medium">
                                {news.author.name}
                            </span>
                        </div>
                    )}
                    
                    <Link
                        href={route('news.show', news.slug)}
                        className="inline-flex items-center gap-1 px-3 py-1.5 bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded-full transition-colors duration-300 shadow-sm hover:shadow-md"
                    >
                        Baca Selengkapnya
                        <svg className="w-3 h-3 ml-1 transform group-hover:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                        </svg>
                    </Link>
                </div>
            </div>
        </article>
    );
}
