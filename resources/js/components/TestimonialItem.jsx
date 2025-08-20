import { motion } from 'framer-motion';
import { Quote, Star } from 'lucide-react';

const TestimonialItem = ({ name, role, content, avatar, rating = 5, delay = 0 }) => {
    return (
        <motion.div
            className="relative rounded-2xl border border-gray-100 bg-white p-8 shadow-lg"
            initial={{ opacity: 0, scale: 0.9 }}
            whileInView={{ opacity: 1, scale: 1 }}
            transition={{
                duration: 0.6,
                delay,
                type: 'spring',
                stiffness: 100,
            }}
            viewport={{ once: true }}
            whileHover={{ scale: 1.02 }}
        >
            {/* Quote Icon */}
            <motion.div
                className="absolute -top-4 left-8 flex h-8 w-8 items-center justify-center rounded-full bg-green-600 shadow-lg"
                initial={{ scale: 0, rotate: -180 }}
                whileInView={{ scale: 1, rotate: 0 }}
                transition={{ duration: 0.8, delay: delay + 0.2 }}
                viewport={{ once: true }}
            >
                <Quote size={16} className="text-white" />
            </motion.div>

            {/* Rating Stars */}
            <motion.div
                className="mb-4 flex items-center space-x-1"
                initial={{ opacity: 0, x: -20 }}
                whileInView={{ opacity: 1, x: 0 }}
                transition={{ duration: 0.6, delay: delay + 0.3 }}
                viewport={{ once: true }}
            >
                {[...Array(5)].map((_, index) => (
                    <motion.div
                        key={index}
                        initial={{ opacity: 0, scale: 0 }}
                        whileInView={{ opacity: 1, scale: 1 }}
                        transition={{
                            duration: 0.4,
                            delay: delay + 0.4 + index * 0.1,
                        }}
                        viewport={{ once: true }}
                    >
                        <Star size={16} className={index < rating ? 'fill-current text-yellow-400' : 'text-gray-300'} />
                    </motion.div>
                ))}
            </motion.div>

            {/* Testimonial Content */}
            <motion.blockquote
                className="mb-6 italic leading-relaxed text-gray-700"
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.6, delay: delay + 0.4 }}
                viewport={{ once: true }}
            >
                "{content}"
            </motion.blockquote>

            {/* Author Info */}
            <motion.div
                className="flex items-center space-x-4"
                initial={{ opacity: 0, y: 20 }}
                whileInView={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.6, delay: delay + 0.5 }}
                viewport={{ once: true }}
            >
                {/* Avatar */}
                <motion.div
                    className="relative flex h-14 w-14 items-center justify-center rounded-full bg-gradient-to-br from-green-400 to-green-600 text-lg font-bold text-white shadow-lg ring-2 ring-white"
                    initial={{ scale: 0 }}
                    whileInView={{ scale: 1 }}
                    transition={{
                        duration: 0.6,
                        delay: delay + 0.6,
                        type: 'spring',
                        stiffness: 150,
                    }}
                    viewport={{ once: true }}
                >
                    {avatar ? (
                        <img
                            src={avatar}
                            alt={name}
                            className="h-full w-full rounded-full object-cover"
                            onError={(e) => {
                                // Fallback to initials if image fails
                                e.target.style.display = 'none';
                                e.target.nextSibling.style.display = 'flex';
                            }}
                        />
                    ) : null}
                    <span className="flex h-full w-full items-center justify-center" style={{ display: avatar ? 'none' : 'flex' }}>
                        {name
                            .split(' ')
                            .map((n) => n[0])
                            .join('')}
                    </span>
                </motion.div>

                {/* Name and Role */}
                <div>
                    <motion.h4
                        className="font-bold text-gray-900"
                        initial={{ opacity: 0, x: -10 }}
                        whileInView={{ opacity: 1, x: 0 }}
                        transition={{ duration: 0.6, delay: delay + 0.6 }}
                        viewport={{ once: true }}
                    >
                        {name}
                    </motion.h4>
                    <motion.p
                        className="text-sm text-gray-500"
                        initial={{ opacity: 0, x: -10 }}
                        whileInView={{ opacity: 1, x: 0 }}
                        transition={{ duration: 0.6, delay: delay + 0.7 }}
                        viewport={{ once: true }}
                    >
                        {role}
                    </motion.p>
                </div>
            </motion.div>
        </motion.div>
    );
};

export default TestimonialItem;
