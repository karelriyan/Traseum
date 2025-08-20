import { motion } from 'framer-motion';

const SectionHeader = ({ title, subtitle, description, centered = true, className = '' }) => {
    const alignment = centered ? 'text-center' : 'text-left';

    return (
        <motion.div
            className={`${alignment} ${className}`}
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6 }}
            viewport={{ once: true }}
        >
            {subtitle && (
                <motion.p
                    className="mb-2 text-sm font-semibold uppercase tracking-wide text-green-600"
                    initial={{ opacity: 0 }}
                    whileInView={{ opacity: 1 }}
                    transition={{ duration: 0.6, delay: 0.1 }}
                    viewport={{ once: true }}
                >
                    {subtitle}
                </motion.p>
            )}

            <motion.h2
                className="mb-4 text-3xl font-bold text-gray-900 md:text-4xl lg:text-5xl"
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.6, delay: 0.2 }}
                viewport={{ once: true }}
            >
                {title}
            </motion.h2>

            {description && (
                <motion.p
                    className="mx-auto max-w-3xl text-lg text-gray-600"
                    initial={{ opacity: 0, y: 20 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.6, delay: 0.3 }}
                    viewport={{ once: true }}
                >
                    {description}
                </motion.p>
            )}
        </motion.div>
    );
};

export default SectionHeader;
