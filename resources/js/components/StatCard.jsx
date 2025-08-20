import { motion } from 'framer-motion';

const StatCard = ({ value, label, suffix = '', prefix = '', icon, delay = 0 }) => {
    return (
        <motion.div
            className="rounded-2xl border border-gray-100 bg-white p-6 text-center shadow-lg"
            initial={{ opacity: 0, scale: 0.8 }}
            whileInView={{ opacity: 1, scale: 1 }}
            transition={{
                duration: 0.6,
                delay,
                type: 'spring',
                stiffness: 100,
            }}
            viewport={{ once: true }}
            whileHover={{ scale: 1.05 }}
        >
            {icon && (
                <motion.div
                    className="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-green-100"
                    initial={{ rotate: -180, opacity: 0 }}
                    whileInView={{ rotate: 0, opacity: 1 }}
                    transition={{ duration: 0.8, delay: delay + 0.2 }}
                    viewport={{ once: true }}
                >
                    <div className="text-2xl text-green-600">{icon}</div>
                </motion.div>
            )}

            <motion.div
                className="mb-2 text-4xl font-bold text-gray-900"
                initial={{ opacity: 0 }}
                whileInView={{ opacity: 1 }}
                transition={{ duration: 0.6, delay: delay + 0.4 }}
                viewport={{ once: true }}
            >
                {prefix}
                <motion.span
                    initial={{ opacity: 0 }}
                    whileInView={{ opacity: 1 }}
                    transition={{ duration: 1, delay: delay + 0.6 }}
                    viewport={{ once: true }}
                >
                    {typeof value === 'number' ? value.toLocaleString('id-ID') : value}
                </motion.span>
                {suffix}
            </motion.div>

            <motion.p
                className="font-medium text-gray-600"
                initial={{ opacity: 0, y: 10 }}
                whileInView={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.6, delay: delay + 0.5 }}
                viewport={{ once: true }}
            >
                {label}
            </motion.p>
        </motion.div>
    );
};

export default StatCard;
