import { motion } from 'framer-motion';
import * as Icons from 'lucide-react';

interface ProgramCardProps {
    title: string;
    description: string;
    icon: string;
    image?: string;
    color?: 'green' | 'blue' | 'purple' | 'yellow';
    delay?: number;
    features?: string[];
}

const ProgramCard = ({ title, description, icon, image, color = 'green', delay = 0, features }: ProgramCardProps) => {
    const IconComponent = (Icons as any)[icon] || Icons.Star;

    const colorVariants = {
        green: {
            bg: 'bg-green-50 hover:bg-green-100',
            icon: 'text-green-600',
            accent: 'border-green-200',
        },
        blue: {
            bg: 'bg-blue-50 hover:bg-blue-100',
            icon: 'text-blue-600',
            accent: 'border-blue-200',
        },
        purple: {
            bg: 'bg-purple-50 hover:bg-purple-100',
            icon: 'text-purple-600',
            accent: 'border-purple-200',
        },
        yellow: {
            bg: 'bg-yellow-50 hover:bg-yellow-100',
            icon: 'text-yellow-600',
            accent: 'border-yellow-200',
        },
    };

    const variant = colorVariants[color] || colorVariants.green;

    return (
        <motion.div
            className={`${variant.bg} ${variant.accent} rounded-2xl border-2 p-8 transition-all duration-300 hover:shadow-lg`}
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            transition={{
                duration: 0.6,
                delay,
                type: 'spring',
                stiffness: 100,
            }}
            viewport={{ once: true }}
            whileHover={{ scale: 1.02 }}
        >
            <motion.div
                className={`h-16 w-16 ${variant.icon} mb-6 flex items-center justify-center`}
                initial={{ scale: 0, rotate: -180 }}
                whileInView={{ scale: 1, rotate: 0 }}
                transition={{
                    duration: 0.8,
                    delay: delay + 0.2,
                    type: 'spring',
                    stiffness: 150,
                }}
                viewport={{ once: true }}
            >
                {image ? <img src={image} alt={title} className="h-16 w-16 object-contain" /> : <IconComponent size={64} />}
            </motion.div>

            <motion.h3
                className="mb-4 text-2xl font-bold text-gray-900"
                initial={{ opacity: 0, x: -20 }}
                whileInView={{ opacity: 1, x: 0 }}
                transition={{ duration: 0.6, delay: delay + 0.3 }}
                viewport={{ once: true }}
            >
                {title}
            </motion.h3>

            <motion.p
                className="leading-relaxed text-gray-600"
                initial={{ opacity: 0, x: -20 }}
                whileInView={{ opacity: 1, x: 0 }}
                transition={{ duration: 0.6, delay: delay + 0.4 }}
                viewport={{ once: true }}
            >
                {description}
            </motion.p>

            {features && features.length > 0 && (
                <motion.ul
                    className="mt-4 space-y-2"
                    initial={{ opacity: 0 }}
                    whileInView={{ opacity: 1 }}
                    transition={{ duration: 0.6, delay: delay + 0.5 }}
                    viewport={{ once: true }}
                >
                    {features.map((feature, index) => (
                        <li key={index} className="flex items-center text-sm text-gray-600">
                            <Icons.CheckCircle size={16} className={`mr-2 ${variant.icon}`} />
                            {feature}
                        </li>
                    ))}
                </motion.ul>
            )}
        </motion.div>
    );
};

export default ProgramCard;
