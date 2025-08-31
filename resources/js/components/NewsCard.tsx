import { motion } from 'framer-motion';
import { ArrowRight, Calendar, Clock, Eye } from 'lucide-react';
import Button from './Button';

interface NewsCardProps {
    id: number;
    title: string;
    excerpt: string;
    image: string;
    category: string;
    categoryColor: string;
    author: string;
    publishedAt: string;
    views: number;
    readTime: string;
    delay?: number;
    featured?: boolean;
}

export default function NewsCard({
    id,
    title,
    excerpt,
    image,
    category,
    categoryColor,
    author,
    publishedAt,
    views,
    readTime,
    delay = 0,
    featured = false,
}: NewsCardProps) {
    const formatDate = (dateString: string) => {
        const date = new Date(dateString);
        return date.toLocaleDateString('id-ID', {
            year: 'numeric',
            month: 'long',
            day: 'numeric',
        });
    };

    if (featured) {
        return (
            <motion.article
                className="group overflow-hidden rounded-xl bg-white shadow-xl transition-all duration-300 hover:translate-y-[-4px] hover:shadow-2xl md:grid md:grid-cols-2 md:items-center"
                initial={{ opacity: 0, y: 30 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.6, delay }}
            >
                <div className="relative overflow-hidden md:h-full">
                    <img
                        src={image}
                        alt={title}
                        className="h-64 w-full object-cover transition-transform duration-300 group-hover:scale-105 md:h-full"
                    />
                    <div className={`absolute left-4 top-4 rounded-full ${categoryColor} px-4 py-2 text-sm font-medium text-white`}>{category}</div>
                    <div className="absolute right-4 top-4 rounded-full bg-black/50 px-3 py-1 text-xs font-medium text-white backdrop-blur-sm">
                        Featured
                    </div>
                </div>
                <div className="p-8">
                    <div className="mb-4 flex items-center space-x-4 text-sm text-gray-500">
                        <div className="flex items-center space-x-1">
                            <Calendar className="h-4 w-4" />
                            <span>{formatDate(publishedAt)}</span>
                        </div>
                        <div className="flex items-center space-x-1">
                            <Eye className="h-4 w-4" />
                            <span>{views.toLocaleString()}</span>
                        </div>
                        <div className="flex items-center space-x-1">
                            <Clock className="h-4 w-4" />
                            <span>{readTime}</span>
                        </div>
                    </div>
                    <h3 className="mb-4 text-2xl font-bold text-gray-900 transition-colors group-hover:text-green-600">{title}</h3>
                    <p className="mb-6 leading-relaxed text-gray-600">{excerpt}</p>
                    <div className="flex items-center justify-between">
                        <span className="text-sm text-gray-500">Oleh {author}</span>
                        <Button variant="primary" size="sm" className="group/btn" href={`/news/${id}`}>
                            Baca Selengkapnya
                            <ArrowRight className="ml-2 h-4 w-4 transition-transform group-hover/btn:translate-x-1" />
                        </Button>
                    </div>
                </div>
            </motion.article>
        );
    }

    return (
        <motion.article
            className="group overflow-hidden rounded-xl bg-white shadow-lg transition-all duration-300 hover:translate-y-[-4px] hover:shadow-xl"
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6, delay }}
        >
            <div className="relative overflow-hidden">
                <img src={image} alt={title} className="h-48 w-full object-cover transition-transform duration-300 group-hover:scale-105" />
                <div className={`absolute left-4 top-4 rounded-full ${categoryColor} px-3 py-1 text-xs font-medium text-white`}>{category}</div>
            </div>
            <div className="p-6">
                <div className="mb-3 flex items-center space-x-4 text-sm text-gray-500">
                    <div className="flex items-center space-x-1">
                        <Calendar className="h-4 w-4" />
                        <span>{formatDate(publishedAt)}</span>
                    </div>
                    <div className="flex items-center space-x-1">
                        <Eye className="h-4 w-4" />
                        <span>{views.toLocaleString()}</span>
                    </div>
                    <div className="flex items-center space-x-1">
                        <Clock className="h-4 w-4" />
                        <span>{readTime}</span>
                    </div>
                </div>
                <h3 className="mb-3 text-lg font-bold text-gray-900 transition-colors group-hover:text-green-600">{title}</h3>
                <p className="mb-4 leading-relaxed text-gray-600">{excerpt}</p>
                <div className="flex items-center justify-between">
                    <span className="text-sm text-gray-500">Oleh {author}</span>
                    <Button variant="outline" size="sm" className="group/btn" href={`/news/${id}`}>
                        Baca
                        <ArrowRight className="ml-2 h-4 w-4 transition-transform group-hover/btn:translate-x-1" />
                    </Button>
                </div>
            </div>
        </motion.article>
    );
}
