import { useInitials } from '@/hooks/use-initials';
import { type User } from '@/types';
import { User as UserIcon } from 'lucide-react';

interface UserAvatarAlternativeProps {
    user: User;
    className?: string;
    size?: 'sm' | 'md' | 'lg';
    variant?: 'initials' | 'icon';
}

export function UserAvatarAlternative({ user, className = '', size = 'md', variant = 'initials' }: UserAvatarAlternativeProps) {
    const getInitials = useInitials();

    const sizeClasses = {
        sm: 'h-6 w-6 text-xs',
        md: 'h-8 w-8 text-sm',
        lg: 'h-10 w-10 text-base',
    };

    // Generate consistent background color based on user name
    const getBackgroundColor = (name: string) => {
        const colors = [
            'bg-emerald-500',
            'bg-blue-500',
            'bg-purple-500',
            'bg-pink-500',
            'bg-indigo-500',
            'bg-green-500',
            'bg-teal-500',
            'bg-cyan-500',
        ];

        const hash = name.split('').reduce((acc, char) => acc + char.charCodeAt(0), 0);
        return colors[hash % colors.length];
    };

    const backgroundClass = getBackgroundColor(user.name);

    return (
        <div
            className={` ${sizeClasses[size]} ${backgroundClass} flex shrink-0 items-center justify-center overflow-hidden rounded-full font-medium text-white ${className} `}
        >
            {variant === 'initials' ? (
                <span className="select-none">{getInitials(user.name)}</span>
            ) : (
                <UserIcon className={` ${size === 'sm' ? 'h-3 w-3' : ''} ${size === 'md' ? 'h-4 w-4' : ''} ${size === 'lg' ? 'h-5 w-5' : ''} `} />
            )}
        </div>
    );
}
