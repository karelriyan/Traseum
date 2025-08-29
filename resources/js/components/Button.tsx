import { Link } from '@inertiajs/react';
import { MouseEventHandler, ReactNode } from 'react';

interface ButtonProps {
    children: ReactNode;
    variant?: 'primary' | 'outline' | 'ghost';
    size?: 'sm' | 'md' | 'lg';
    href?: string;
    onClick?: MouseEventHandler<HTMLButtonElement>;
    disabled?: boolean;
    className?: string;
    [key: string]: any; // for other props
}

const Button = ({ children, variant = 'primary', size = 'md', href, onClick, disabled = false, className = '', ...props }: ButtonProps) => {
    const baseClasses =
        'inline-flex items-center justify-center font-medium transition-all duration-200 focus:outline-none focus:ring-4 disabled:opacity-50 disabled:cursor-not-allowed';

    const variants = {
        primary: 'bg-green-600 hover:bg-green-700 text-white focus:ring-green-500/20 shadow-sm',
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
