import { Link } from '@inertiajs/react';

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

export default function NewsCard({ news, size = 'medium', showExcerpt = true, showAuthor = true, showViews = true }: NewsCardProps) {
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
            { label: 'menit', seconds: 60 },
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
        small: '',
        medium: '',
        large: '',
    };

    const titleSizeClasses = {
        small: 'text-base',
        medium: 'text-lg',
        large: 'text-xl',
    };

    return (
        <Link href={route('news.show', news.slug)} className="group block h-full">
            <article className="flex h-full flex-col overflow-hidden rounded-2xl border border-slate-100 bg-white shadow-sm transition-all duration-500 hover:-translate-y-1 hover:border-slate-200 hover:shadow-xl">
                {news.featured_image_url ? (
                    <div className="aspect-[4/3] flex-shrink-0 overflow-hidden bg-gradient-to-br from-slate-100 to-slate-200">
                        <img
                            src={news.featured_image_url}
                            alt={news.title}
                            className="h-full w-full bg-white object-contain transition-transform duration-700 group-hover:scale-105"
                            loading="lazy"
                        />
                    </div>
                ) : (
                    <div className="flex aspect-[4/3] flex-shrink-0 items-center justify-center border-b border-slate-100 bg-gradient-to-br from-slate-50 to-slate-100">
                        <svg className="h-16 w-16 text-slate-300" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                fillRule="evenodd"
                                d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                clipRule="evenodd"
                            />
                        </svg>
                    </div>
                )}

                <div className="flex flex-grow flex-col p-4">
                    <div className="mb-3 flex items-center justify-end">
                        <div className="flex items-center gap-2 text-xs text-slate-500">
                            <span className="flex items-center gap-1">
                                <svg className="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth={2}
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
                                    />
                                </svg>
                                {formatRelativeTime(news.published_at)}
                            </span>
                            {showViews && (
                                <span className="flex items-center gap-1">
                                    <EyeIcon className="h-3 w-3" />
                                    {news.views_count.toLocaleString('id-ID')}
                                </span>
                            )}
                        </div>
                    </div>

                    <h3
                        className={`mb-4 line-clamp-2font-bold text-slate-900 transition-colors duration-300 group-hover:text-green-600 ${titleSizeClasses[size]} flex-grow leading-tight`}
                    >
                        {news.title}
                    </h3>

                    <div className="mt-auto flex justify-end">
                        <div className="flex items-center gap-1 text-xs font-semibold text-green-600 transition-colors group-hover:text-green-700">
                            <span>Baca Selengkapnya</span>
                            <svg
                                className="h-3 w-3 transition-transform duration-300 group-hover:translate-x-1"
                                fill="none"
                                stroke="currentColor"
                                viewBox="0 0 24 24"
                            >
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </article>
        </Link>
    );
}
