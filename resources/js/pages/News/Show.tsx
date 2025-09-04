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

            {/* Article Header */}
            <section className="bg-white py-8">
                <div className="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                    {/* Breadcrumb */}
                    <nav className="mb-6 text-xs">
                        <div className="flex items-center gap-2 text-slate-500">
                            <Link href={route('news.index')} className="hover:text-green-600">
                                Berita
                            </Link>
                            <span>/</span>
                            <span className="text-slate-900">{categoryName}</span>
                        </div>
                    </nav>

                    {/* Article Meta */}
                    <div className="mb-4 flex flex-wrap items-center gap-3 text-xs text-slate-600">
                        <span className="inline-flex items-center gap-1 rounded-full bg-green-100 px-2 py-1 font-medium text-green-700">
                            {categoryName}
                        </span>
                        <div className="flex items-center gap-1">
                            <CalendarIcon className="h-3 w-3" />
                            <span>{formatDate(news.published_at)}</span>
                        </div>
                        <div className="flex items-center gap-1">
                            <EyeIcon className="h-3 w-3" />
                            <span>{formatNumber(news.views_count)} dibaca</span>
                        </div>
                        <div className="flex items-center gap-1">
                            <UserIcon className="h-3 w-3" />
                            <span>Oleh {news.author.name}</span>
                        </div>
                    </div>

                    {/* Article Title */}
                    <h1 className="mb-4 text-2xl font-bold leading-tight text-slate-900 md:text-3xl lg:text-4xl">{news.title}</h1>

                    {/* Article Excerpt */}
                    {news.excerpt && <p className="mb-6 text-base leading-relaxed text-slate-600 md:text-lg">{news.excerpt}</p>}

                    {/* Featured Image */}
                    {news.featured_image_url && (
                        <div className="mb-8 overflow-hidden rounded-2xl">
                            <img src={news.featured_image_url} alt={news.title} className="h-auto w-full object-cover" />
                        </div>
                    )}
                </div>
            </section>

            {/* Article Content */}
            <section className="bg-white pb-12">
                <div className="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                    {/* Main Article Content */}
                    <div className="prose prose-base prose-slate max-w-none">
                        <div
                            dangerouslySetInnerHTML={{ __html: news.content }}
                            className="leading-relaxed text-slate-800 [&>a]:text-green-600 [&>a]:underline [&>a]:decoration-green-300 [&>a]:underline-offset-2 hover:[&>a]:decoration-green-500 [&>blockquote]:my-6 [&>blockquote]:border-l-4 [&>blockquote]:border-green-500 [&>blockquote]:bg-green-50 [&>blockquote]:py-3 [&>blockquote]:pl-4 [&>blockquote]:pr-3 [&>blockquote]:italic [&>blockquote]:text-slate-700 [&>code]:rounded [&>code]:bg-slate-100 [&>code]:px-2 [&>code]:py-1 [&>code]:font-mono [&>code]:text-sm [&>code]:text-slate-800 [&>em]:italic [&>em]:text-slate-600 [&>h2]:mb-4 [&>h2]:mt-8 [&>h2]:border-b [&>h2]:border-slate-200 [&>h2]:pb-2 [&>h2]:text-xl [&>h2]:font-bold [&>h2]:text-slate-900 [&>h3]:mb-3 [&>h3]:mt-6 [&>h3]:text-lg [&>h3]:font-semibold [&>h3]:text-slate-900 [&>h4]:mb-2 [&>h4]:mt-4 [&>h4]:text-base [&>h4]:font-medium [&>h4]:text-slate-800 [&>img]:my-6 [&>img]:h-auto [&>img]:w-full [&>img]:rounded-xl [&>img]:shadow-lg [&>li]:text-base [&>li]:leading-relaxed [&>li]:text-slate-700 [&>ol]:mb-4 [&>ol]:space-y-1 [&>ol]:pl-4 [&>p]:mb-4 [&>p]:text-base [&>p]:leading-7 [&>pre]:my-4 [&>pre]:overflow-x-auto [&>pre]:rounded-lg [&>pre]:bg-slate-900 [&>pre]:p-3 [&>pre]:text-white [&>strong]:font-semibold [&>strong]:text-slate-900 [&>table]:my-6 [&>table]:w-full [&>table]:border-collapse [&>table]:border [&>table]:border-slate-300 [&>td]:border [&>td]:border-slate-300 [&>td]:px-3 [&>td]:py-2 [&>td]:text-slate-700 [&>th]:border [&>th]:border-slate-300 [&>th]:bg-slate-50 [&>th]:px-3 [&>th]:py-2 [&>th]:text-left [&>th]:font-semibold [&>th]:text-slate-900 [&>ul]:mb-4 [&>ul]:space-y-1 [&>ul]:pl-4"
                        />
                    </div>

                    {/* Article Footer */}
                    <div className="mt-16 border-t border-slate-200 pt-8">
                        <div className="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
                            {/* Author Info */}
                            <div className="flex items-center gap-3">
                                <div className="flex h-10 w-10 items-center justify-center rounded-full bg-green-600 text-base font-bold text-white">
                                    {news.author.name.charAt(0).toUpperCase()}
                                </div>
                                <div>
                                    <div className="text-sm font-semibold text-slate-900">{news.author.name}</div>
                                    <div className="text-xs text-slate-600">Penulis</div>
                                </div>
                            </div>

                            {/* Share Buttons */}
                            <div className="flex items-center gap-2">
                                <span className="text-xs font-medium text-slate-700">Bagikan:</span>
                                <div className="flex gap-2">
                                    <button
                                        onClick={() => {
                                            const url = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(shareUrl)}`;
                                            window.open(url, '_blank', 'width=600,height=400');
                                        }}
                                        className="flex h-8 w-8 items-center justify-center rounded-full bg-blue-600 text-white transition-colors hover:bg-blue-700"
                                        title="Bagikan ke Facebook"
                                    >
                                        <svg className="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
                                        </svg>
                                    </button>
                                    <button
                                        onClick={() => {
                                            const url = `https://twitter.com/intent/tweet?url=${encodeURIComponent(shareUrl)}&text=${encodeURIComponent(news.title)}`;
                                            window.open(url, '_blank', 'width=600,height=400');
                                        }}
                                        className="flex h-8 w-8 items-center justify-center rounded-full bg-sky-500 text-white transition-colors hover:bg-sky-600"
                                        title="Bagikan ke Twitter"
                                    >
                                        <svg className="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z" />
                                        </svg>
                                    </button>
                                    <button
                                        onClick={() => {
                                            const url = `https://wa.me/?text=${encodeURIComponent(news.title + ' - ' + shareUrl)}`;
                                            window.open(url, '_blank');
                                        }}
                                        className="flex h-8 w-8 items-center justify-center rounded-full bg-green-600 text-white transition-colors hover:bg-green-700"
                                        title="Bagikan ke WhatsApp"
                                    >
                                        <svg className="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488z" />
                                        </svg>
                                    </button>
                                    <button
                                        onClick={() => {
                                            navigator.clipboard.writeText(shareUrl);
                                            alert('Link berhasil disalin!');
                                        }}
                                        className="flex h-8 w-8 items-center justify-center rounded-full bg-slate-600 text-white transition-colors hover:bg-slate-700"
                                        title="Salin Link"
                                    >
                                        <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBo   x="0 0 24 24">
                                            <path
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                strokeWidth={2}
                                                d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"
                                            />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        {/* Tags */}
                        {news.tags && news.tags.length > 0 && (
                            <div className="mt-6 border-t border-slate-200 pt-6">
                                <div className="flex flex-wrap gap-1">
                                    <span className="text-xs font-medium text-slate-700">Tags:</span>
                                    {news.tags.map((tag, index) => (
                                        <span
                                            key={index}
                                            className="inline-flex cursor-pointer items-center rounded-full bg-slate-100 px-2 py-1 text-xs font-medium text-slate-700 transition-colors hover:bg-slate-200"
                                        >
                                            #{tag}
                                        </span>
                                    ))}
                                </div>
                            </div>
                        )}
                    </div>
                </div>
            </section>

            {/* Navigation */}
            {(previousNews || nextNews) && (
                <section className="bg-slate-50 py-12">
                    <div className="mx-auto max-w-4xl px-4 sm:px-6 lg:px-8">
                        <div className="grid grid-cols-1 gap-6 md:grid-cols-2">
                            {previousNews && (
                                <Link
                                    href={route('news.show', previousNews.slug)}
                                    className="group rounded-xl border border-slate-200 bg-white p-4 shadow-lg transition-all duration-300 hover:border-green-300 hover:shadow-xl"
                                >
                                    <div className="mb-2 flex items-center gap-2 text-sm font-semibold text-green-600">
                                        <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                                        </svg>
                                        <span>Artikel Sebelumnya</span>
                                    </div>
                                    <h3 className="line-clamp-2 text-base font-bold text-slate-900 transition-colors group-hover:text-green-700">
                                        {previousNews.title}
                                    </h3>
                                </Link>
                            )}

                            {nextNews && (
                                <Link
                                    href={route('news.show', nextNews.slug)}
                                    className="group rounded-xl border border-slate-200 bg-white p-4 text-right shadow-lg transition-all duration-300 hover:border-green-300 hover:shadow-xl"
                                >
                                    <div className="mb-2 flex items-center justify-end gap-2 text-sm font-semibold text-green-600">
                                        <span>Artikel Selanjutnya</span>
                                        <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </div>
                                    <h3 className="line-clamp-2 text-base font-bold text-slate-900 transition-colors group-hover:text-green-700">
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
                <section className="bg-white py-20">
                    <div className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                        <div className="mb-16 text-center">
                            <div className="mb-6 inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-100 px-5 py-2.5 font-medium text-slate-700">
                                <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path
                                        strokeLinecap="round"
                                        strokeLinejoin="round"
                                        strokeWidth={1.5}
                                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"
                                    />
                                </svg>
                                <span className="text-sm font-semibold uppercase tracking-wide">Artikel Terkait</span>
                            </div>
                            <h2 className="mb-4 text-3xl font-bold text-slate-900 md:text-4xl">Baca Juga Artikel Menarik Lainnya</h2>
                            <div className="mx-auto mb-6 h-1 w-24 rounded-full bg-gradient-to-r from-green-500 to-green-600"></div>
                            <p className="mx-auto max-w-2xl text-lg text-slate-600">Temukan artikel serupa yang mungkin menarik bagi Anda</p>
                        </div>

                        <div className="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                            {relatedNews.map((item) => (
                                <NewsCard key={item.id} news={item} size="small" showExcerpt={true} showAuthor={false} showViews={false} />
                            ))}
                        </div>
                    </div>
                </section>
            )}
        </NewsLayout>
    );
}
