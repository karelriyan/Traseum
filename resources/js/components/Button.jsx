import { Link } from '@inertiajs/react';

const Button = ({ children, variant = 'primary', size = 'md', href, onClick, disabled = false, className = '', ...props }) => {
    const baseClasses =
        'inline-flex items-center justify-center font-medium transition-all duration-200 focus:outline-none focus:ring-4 disabled:opacity-50 disabled:cursor-not-allowed';

    const variants = {
        primary: 'bg-green-600 hover:bg-white-700 text-green focus:ring-green-500/20',
        outline: 'border-2 border-green-600 text-green-600 hover:bg-green-600 hover:text-white focus:ring-green-500/20',
        ghost: 'text-green-600 hover:bg-green-50 focus:ring-green-500/20',
    };

    const sizes = {
        sm: 'px-3 py-2 text-sm rounded',
        md: 'px-6 py-3 text-base rounded-lg',
        lg: 'px-8 py-4 text-lg rounded-xl',
    };

    const classes = `${baseClasses} ${variants[variant]} ${sizes[size]} ${className}`;

    if (href) {
        return (
            <Link href={href} className={classes} {...props}>
                {children}
            </Link>
        );
    }

    return (
        <button className={classes} onClick={onClick} disabled={disabled} {...props}>
            {children}
        </button>
    );
};

export default Button;
