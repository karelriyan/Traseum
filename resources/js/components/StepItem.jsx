import { motion } from 'framer-motion';
import * as Icons from 'lucide-react';

const StepItem = ({ number, title, description, icon, isLast = false, delay = 0 }) => {
    const IconComponent = Icons[icon] || Icons.Circle;

    return (
        <div className="relative">
            <motion.div
                className="flex items-start space-x-6"
                initial={{ opacity: 0, x: -30 }}
                whileInView={{ opacity: 1, x: 0 }}
                transition={{ duration: 0.6, delay }}
                viewport={{ once: true }}
            >
                {/* Step Number & Icon */}
                <div className="flex-shrink-0">
                    <motion.div
                        className="relative"
                        initial={{ scale: 0 }}
                        whileInView={{ scale: 1 }}
                        transition={{
                            duration: 0.8,
                            delay: delay + 0.2,
                            type: 'spring',
                            stiffness: 150,
                        }}
                        viewport={{ once: true }}
                    >
                        {/* Circle with number */}
                        <div className="flex h-16 w-16 items-center justify-center rounded-full bg-green-600 text-xl font-bold text-white shadow-lg">
                            {number}
                        </div>

                        {/* Icon overlay */}
                        <motion.div
                            className="absolute -right-2 -top-2 flex h-8 w-8 items-center justify-center rounded-full bg-yellow-400 shadow-md"
                            initial={{ scale: 0, rotate: -90 }}
                            whileInView={{ scale: 1, rotate: 0 }}
                            transition={{
                                duration: 0.6,
                                delay: delay + 0.4,
                                type: 'spring',
                                stiffness: 200,
                            }}
                            viewport={{ once: true }}
                        >
                            <IconComponent size={16} className="text-white" />
                        </motion.div>
                    </motion.div>

                    {/* Connecting Line */}
                    {!isLast && (
                        <motion.div
                            className="absolute left-8 top-16 h-20 w-px bg-gradient-to-b from-green-300 to-transparent"
                            initial={{ scaleY: 0 }}
                            whileInView={{ scaleY: 1 }}
                            transition={{ duration: 0.8, delay: delay + 0.6 }}
                            viewport={{ once: true }}
                        />
                    )}
                </div>

                {/* Content */}
                <div className="flex-1 pt-3">
                    <motion.h3
                        className="mb-3 text-2xl font-bold text-gray-900"
                        initial={{ opacity: 0, y: 10 }}
                        whileInView={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.6, delay: delay + 0.3 }}
                        viewport={{ once: true }}
                    >
                        {title}
                    </motion.h3>

                    <motion.p
                        className="leading-relaxed text-gray-600"
                        initial={{ opacity: 0, y: 10 }}
                        whileInView={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.6, delay: delay + 0.4 }}
                        viewport={{ once: true }}
                    >
                        {description}
                    </motion.p>
                </div>
            </motion.div>
        </div>
    );
};

export default StepItem;
