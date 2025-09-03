import NewsCard from '@/components/News/NewsCard';
import NewsLayout from '@/layouts/NewsLayout';
import { formatDate, formatNumber } from '@/Utils/dateHelpers';
import { Head, Link } from '@inertiajs/react';

// Simple SVG Icons
const CalendarIcon = ({ className }: { className?: string }) => (
    <svg className={className} fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path
            strokeLinecap="round"
            strokeLinejoin="round"
            strokeWidth={2}
            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
        />
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

const UserIcon = ({ className }: { className?: string }) => (
    <svg className={className} fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
    </svg>
);

const ShareIcon = ({ className }: { className?: string }) => (
    <svg className={className} fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path
            strokeLinecap="round"
            strokeLinejoin="round"
            strokeWidth={2}
            d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"
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
        <NewsLayout backUrl={route('news.index')} backLabel="Kembali ke Berita">
            <Head title={news.meta_title || news.title}>
                <meta name="description" content={news.meta_description || news.excerpt} />
                <meta property="og:title" content={news.title} />
                <meta property="og:description" content={news.excerpt} />
                {news.featured_image_url && <meta property="og:image" content={news.featured_image_url} />}
                <meta property="og:url" content={shareUrl} />
                <meta property="og:type" content="article" />
            </Head>

            {/* Hero Section with Article Image */}
            <section className="relative overflow-hidden bg-gradient-to-r from-gray-900 to-gray-800 text-white">
                {news.featured_image_url && (
                    <div className="absolute inset-0">
                        <img src={news.featured_image_url} alt={news.title} className="h-full w-full object-cover opacity-30" />
                        <div className="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-black/20" />
                    </div>
                )}

                <div className="relative mx-auto max-w-4xl px-4 py-20 sm:px-6 lg:px-8">
                    {/* Article Meta */}
                    <div className="mb-6 flex items-center gap-4 text-sm text-white/80">
                        <span className="rounded-full bg-green-500 px-4 py-2 font-bold text-white">{categoryName}</span>
                        <div className="flex items-center gap-2">
                            <CalendarIcon className="h-4 w-4" />
                            <span>{formatDate(news.published_at)}</span>
                        </div>
                        <div className="flex items-center gap-2">
                            <EyeIcon className="h-4 w-4" />
                            <span>{formatNumber(news.views_count)} views</span>
                        </div>
                    </div>

                    <h1 className="mb-6 text-4xl font-bold leading-tight md:text-6xl">{news.title}</h1>

                    {news.excerpt && <p className="max-w-3xl text-xl leading-relaxed text-white/90 md:text-2xl">{news.excerpt}</p>}

                    {/* Author & Share */}
                    <div className="mt-8 flex items-center justify-between border-t border-white/20 pt-6">
                        <div className="flex items-center gap-3">
                            <div className="flex h-10 w-10 items-center justify-center rounded-full bg-green-500 font-bold text-white">
                                {news.author.name.charAt(0).toUpperCase()}
                            </div>
                            <div>
                                <div className="font-semibold text-white">{news.author.name}</div>
                                <div className="text-sm text-white/60">Penulis</div>
                            </div>
                        </div>

                        <button
                            onClick={handleShare}
                            className="flex items-center gap-2 rounded-full bg-white/10 px-6 py-3 text-white backdrop-blur-sm transition-all duration-300 hover:bg-white/20"
                        >
                            <ShareIcon className="h-5 w-5" />
                            <span className="font-medium">Bagikan</span>
                        </button>
                    </div>
                </div>
            </section>

            {/* Article Content */}
            <section className="mx-auto max-w-4xl bg-white px-4 py-16 sm:px-6 lg:px-8">
                <article className="prose prose-lg prose-green max-w-none">
                    <div
                        dangerouslySetInnerHTML={{ __html: news.content }}
                        className="leading-relaxed text-gray-800 [&>blockquote]:rounded-r [&>blockquote]:border-l-4 [&>blockquote]:border-green-500 [&>blockquote]:bg-green-50 [&>blockquote]:py-2 [&>blockquote]:pl-4 [&>blockquote]:italic [&>blockquote]:text-gray-600 [&>h1]:mb-6 [&>h1]:mt-8 [&>h1]:text-3xl [&>h1]:font-bold [&>h1]:text-gray-900 [&>h2]:mb-4 [&>h2]:mt-8 [&>h2]:text-2xl [&>h2]:font-bold [&>h2]:text-gray-900 [&>h3]:mb-4 [&>h3]:mt-6 [&>h3]:text-xl [&>h3]:font-bold [&>h3]:text-gray-900 [&>img]:my-6 [&>img]:rounded-lg [&>img]:shadow-lg [&>li]:mb-2 [&>li]:text-gray-700 [&>p]:mb-4 [&>p]:leading-relaxed [&>p]:text-gray-700 [&>ul]:mb-4 [&>ul]:pl-6"
                    />
                </article>

                {/* Tags */}
                {news.tags && news.tags.length > 0 && (
                    <div className="mt-12 border-t border-gray-200 pt-8">
                        <h3 className="mb-4 flex items-center gap-2 text-lg font-semibold text-gray-900">
                            <svg className="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                            </svg>
                            Tags
                        </h3>
                        <div className="flex flex-wrap gap-3">
                            {news.tags.map((tag, index) => (
                                <span
                                    key={index}
                                    className="cursor-pointer rounded-full bg-gradient-to-r from-green-100 to-green-50 px-4 py-2 text-sm font-medium text-green-800 transition-all duration-300 hover:from-green-200 hover:to-green-100"
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
                    <div className="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                        <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                            {previousNews && (
                                <Link
                                    href={route('news.show', previousNews.slug)}
                                    className="group rounded-xl border border-gray-100 bg-white p-6 shadow-lg transition-all duration-300 hover:border-green-200 hover:shadow-xl"
                                >
                                    <div className="mb-3 flex items-center gap-2 font-medium text-green-600">
                                        <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                        </svg>
                                        <span>Artikel Sebelumnya</span>
                                    </div>
                                    <h3 className="line-clamp-2 font-bold text-gray-900 transition-colors group-hover:text-green-700">
                                        {previousNews.title}
                                    </h3>
                                </Link>
                            )}

                            {nextNews && (
                                <Link
                                    href={route('news.show', nextNews.slug)}
                                    className="group rounded-xl border border-gray-100 bg-white p-6 text-right shadow-lg transition-all duration-300 hover:border-green-200 hover:shadow-xl"
                                >
                                    <div className="mb-3 flex items-center justify-end gap-2 font-medium text-green-600">
                                        <span>Artikel Selanjutnya</span>
                                        <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </div>
                                    <h3 className="line-clamp-2 font-bold text-gray-900 transition-colors group-hover:text-green-700">
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
                    <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div className="mb-12 text-center">
                            <div className="mb-4 inline-flex items-center gap-2 rounded-full bg-green-100 px-4 py-2 font-semibold text-green-800">
                                🔗 Artikel Terkait
                            </div>
                            <h2 className="text-3xl font-bold text-gray-900 md:text-4xl">Baca Juga Artikel Menarik Lainnya</h2>
                            <p className="mt-4 text-lg text-gray-600">Temukan artikel serupa yang mungkin menarik bagi Anda</p>
                        </div>

                        <div className="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
                            {relatedNews.map((item) => (
                                <NewsCard key={item.id} news={item} size="small" showExcerpt={false} />
                            ))}
                        </div>
                    </div>
                </section>
            )}
        </NewsLayout>
    );
}
