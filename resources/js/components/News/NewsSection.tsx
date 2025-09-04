import NewsCard from '@/components/News/NewsCard';
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

interface NewsSectionProps {
    latestNews: NewsItem[];
    title?: string;
    subtitle?: string;
}

export default function NewsSection({
    latestNews,
    title = 'Berita & Publikasi',
    subtitle = 'Ikuti perkembangan terkini dan informasi terbaru dari Bank Sampah Cipta Muri',
}: NewsSectionProps) {
    if (!latestNews || latestNews.length === 0) {
        return null;
    }

    return (
        <section className="relative bg-white py-24">
            {/* Subtle Background Pattern */}
            <div className="absolute inset-0 opacity-[0.03]">
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

            <div className="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                {/* Section Header */}
                <div className="mb-20 text-center">
                    <div className="mb-8 inline-flex items-center gap-2 rounded-full border border-slate-200 bg-slate-100 px-5 py-2.5 font-medium text-slate-700">
                        <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path
                                strokeLinecap="round"
                                strokeLinejoin="round"
                                strokeWidth={1.5}
                                d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"
                            />
                        </svg>
                        <span className="text-sm font-semibold uppercase tracking-wide">Pusat Informasi</span>
                    </div>

                    <h2 className="mb-6 text-4xl font-bold tracking-tight text-slate-900 md:text-5xl lg:text-6xl">{title}</h2>

                    <div className="mx-auto mb-8 h-1 w-24 rounded-full bg-gradient-to-r from-green-500 to-green-600"></div>

                    <p className="mx-auto max-w-2xl text-lg font-light leading-relaxed text-slate-600">{subtitle}</p>
                </div>

                {/* Featured News Grid */}
                <div className="mb-16 grid grid-cols-1 gap-8 lg:grid-cols-12">
                    {/* Main Featured Article */}
                    {latestNews[0] && (
                        <div className="lg:col-span-8">
                            <Link href={route('news.show', latestNews[0].slug)} className="group block">
                                <article className="relative h-[500px] overflow-hidden rounded-3xl border border-slate-100 bg-white shadow-lg transition-all duration-700 hover:border-slate-200 hover:shadow-2xl">
                                    {latestNews[0].featured_image_url ? (
                                        <div className="absolute inset-0">
                                            <img
                                                src={latestNews[0].featured_image_url}
                                                alt={latestNews[0].title}
                                                className="h-full w-full object-cover transition-transform duration-1000 group-hover:scale-[1.02]"
                                            />
                                            <div className="absolute inset-0 bg-gradient-to-t from-slate-900/90 via-slate-900/30 to-transparent" />
                                        </div>
                                    ) : (
                                        <div className="absolute inset-0 flex items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200">
                                            <svg className="h-24 w-24 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path
                                                    fillRule="evenodd"
                                                    d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                    clipRule="evenodd"
                                                />
                                            </svg>
                                            <div className="absolute inset-0 bg-gradient-to-t from-slate-700/60 to-transparent" />
                                        </div>
                                    )}

                                    <div className="absolute bottom-0 left-0 right-0 p-10">
                                        <div className="mb-6 flex items-center gap-3">
                                            <span className="rounded-full bg-green-500 px-4 py-2 text-sm font-bold tracking-wide text-white shadow-lg">
                                                FEATURED
                                            </span>
                                            <span className="text-sm font-medium text-white/90">
                                                {new Date(latestNews[0].published_at).toLocaleDateString('id-ID', {
                                                    day: 'numeric',
                                                    month: 'long',
                                                    year: 'numeric',
                                                })}
                                            </span>
                                        </div>

                                        <h3 className="mb-4 line-clamp-2 text-2xl font-bold leading-tight text-white transition-colors duration-500 group-hover:text-green-300 md:text-4xl">
                                            {latestNews[0].title}
                                        </h3>

                                        <p className="mb-6 line-clamp-2 text-lg leading-relaxed text-white/90">{latestNews[0].excerpt}</p>

                                        <div className="flex items-center justify-between">
                                            <div className="flex items-center gap-3 text-white/90">
                                                <div className="flex h-10 w-10 items-center justify-center rounded-full border border-white/20 bg-white/20 font-bold text-white backdrop-blur-sm">
                                                    {latestNews[0].author.name.charAt(0).toUpperCase()}
                                                </div>
                                                <div>
                                                    <div className="font-medium text-white">{latestNews[0].author.name}</div>
                                                    <div className="text-sm text-white/70">Penulis</div>
                                                </div>
                                            </div>
                                            <div className="flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-3 py-2 text-white/80 backdrop-blur-sm">
                                                <svg className="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        strokeWidth={2}
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                    />
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        strokeWidth={2}
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                    />
                                                </svg>
                                                <span className="font-medium">{latestNews[0].views_count.toLocaleString('id-ID')}</span>
                                            </div>
                                        </div>
                                    </div>
                                </article>
                            </Link>
                        </div>
                    )}

                    {/* Side Articles */}
                    <div className="space-y-6 lg:col-span-4">
                        {latestNews.slice(1, 4).map((item, index) => (
                            <Link key={item.id} href={route('news.show', item.slug)} className="group block">
                                <article className="rounded-2xl border border-slate-100 bg-white p-6 shadow-sm transition-all duration-500 hover:border-slate-200 hover:shadow-lg">
                                    <div className="flex gap-4">
                                        <div className="h-20 w-20 flex-shrink-0 overflow-hidden rounded-xl bg-slate-100">
                                            {item.featured_image_url ? (
                                                <img
                                                    src={item.featured_image_url}
                                                    alt={item.title}
                                                    className="h-full w-full bg-white object-contain transition-transform duration-700 group-hover:scale-110"
                                                />
                                            ) : (
                                                <div className="flex h-full w-full items-center justify-center bg-gradient-to-br from-slate-100 to-slate-200">
                                                    <svg className="h-8 w-8 text-slate-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path
                                                            fillRule="evenodd"
                                                            d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z"
                                                            clipRule="evenodd"
                                                        />
                                                    </svg>
                                                </div>
                                            )}
                                        </div>
                                        <div className="min-w-0 flex-1">
                                            <div className="mb-2 flex items-center gap-2">
                                                <span className="text-xs font-semibold tracking-wide text-slate-500">
                                                    {new Date(item.published_at).toLocaleDateString('id-ID', {
                                                        day: 'numeric',
                                                        month: 'short',
                                                    })}
                                                </span>
                                                <div className="flex items-center gap-1 text-xs text-slate-400">
                                                    <svg className="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            strokeWidth={2}
                                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                                                        />
                                                        <path
                                                            strokeLinecap="round"
                                                            strokeLinejoin="round"
                                                            strokeWidth={2}
                                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"
                                                        />
                                                    </svg>
                                                    <span>{item.views_count}</span>
                                                </div>
                                            </div>
                                            <h4 className="line-clamp-2 text-sm font-bold leading-tight text-slate-900 transition-colors duration-300 group-hover:text-green-600">
                                                {item.title}
                                            </h4>
                                        </div>
                                    </div>
                                </article>
                            </Link>
                        ))}
                    </div>
                </div>

                {/* More Articles Grid */}
                {latestNews.length > 4 && (
                    <div className="mb-20 grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
                        {latestNews.slice(4, 7).map((item) => (
                            <NewsCard key={item.id} news={item} size="medium" showExcerpt={true} showAuthor={true} showViews={true} />
                        ))}
                    </div>
                )}

                {/* Call to Action */}
                <div className="rounded-3xl border border-slate-100 bg-gradient-to-br from-slate-50 to-white p-12 text-center">
                    <h3 className="mb-4 text-2xl font-bold text-slate-900">Jangan Lewatkan Update Terbaru</h3>
                    <p className="mx-auto mb-8 max-w-lg text-slate-600">
                        Dapatkan informasi terdepan tentang perkembangan Bank Sampah Cipta Muri dan program-program inovatif kami.
                    </p>
                    <Link
                        href={route('news.index')}
                        className="group inline-flex transform items-center gap-3 rounded-2xl bg-slate-900 px-8 py-4 font-semibold text-white shadow-lg transition-all duration-300 hover:-translate-y-0.5 hover:bg-slate-800 hover:shadow-xl"
                    >
                        <span>Lihat Semua Berita</span>
                        <svg
                            className="h-5 w-5 transition-transform duration-300 group-hover:translate-x-1"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M17 8l4 4m0 0l-4 4m4-4H3" />
                        </svg>
                    </Link>
                </div>
            </div>
        </section>
    );
}
