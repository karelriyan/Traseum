import { Button } from '@/components/ui/button';
import { UserAvatarAlternative } from '@/components/user-avatar-alternative';
import { useInitials } from '@/hooks/use-initials';
import { type User } from '@/types';
import { router } from '@inertiajs/react';
import { LayoutDashboard, LogOut } from 'lucide-react';
import { useEffect, useRef, useState } from 'react';

interface SimpleUserDropdownProps {
    user: User;
    variant?: 'default' | 'navbar';
    className?: string;
}

export function SimpleUserDropdown({ user, variant = 'default', className }: SimpleUserDropdownProps) {
    const getInitials = useInitials();
    const [isOpen, setIsOpen] = useState(false);
    const [isLoading, setIsLoading] = useState(false);
    const [showTooltip, setShowTooltip] = useState(false);
    const dropdownRef = useRef<HTMLDivElement>(null);

    // Debug logging for avatar
    useEffect(() => {
        console.log('User data in dropdown:', user);
        console.log('Avatar URL:', user.avatar);
    }, [user]);

    useEffect(() => {
        function handleClickOutside(event: MouseEvent) {
            if (dropdownRef.current && !dropdownRef.current.contains(event.target as Node)) {
                setIsOpen(false);
            }
        }

        document.addEventListener('mousedown', handleClickOutside);
        return () => {
            document.removeEventListener('mousedown', handleClickOutside);
        };
    }, []);

    const handleLogout = async () => {
        setIsLoading(true);
        setIsOpen(false);
        try {
            router.post(route('logout'));
        } finally {
            setIsLoading(false);
        }
    };

    const handleAdminClick = () => {
        setIsLoading(true);
        setIsOpen(false);
        window.location.href = '/admin';
    };

    const handleRefreshProfile = () => {
        setIsOpen(false);
        // Force refresh to get updated user data
        router.reload({ only: ['auth.user'] });
    };

    return (
        <div className="group relative" ref={dropdownRef}>
            {/* Bottom Tooltip for all screen sizes - hides when dropdown is open */}
            <div
                className={`pointer-events-none absolute left-1/2 top-full z-50 mt-2 -translate-x-1/2 transform transition-all duration-300 ${
                    isOpen ? 'translate-y-0 opacity-0' : 'opacity-0 group-hover:translate-y-1 group-hover:opacity-100'
                }`}
            >
                <div className="relative rounded-lg border border-gray-600 bg-gradient-to-r from-gray-800 to-gray-900 px-4 py-2 text-sm text-white shadow-xl backdrop-blur-sm">
                    <div className="flex items-center gap-2 whitespace-nowrap">
                        <span className="max-w-40 truncate font-medium">{user.name}</span>
                    </div>

                    {/* Bottom tooltip arrow (pointing up) */}
                    <div className="absolute bottom-full left-1/2 -translate-x-1/2 transform">
                        <div className="border-b-4 border-l-4 border-r-4 border-transparent border-b-gray-900"></div>
                    </div>
                </div>
            </div>

            <Button
                variant="ghost"
                onClick={() => setIsOpen(!isOpen)}
                className={`group size-10 rounded-full p-1 transition-all duration-300 hover:scale-105 hover:shadow-lg active:scale-95 ${
                    variant === 'navbar'
                        ? 'border-2 border-white/20 bg-white/10 backdrop-blur-sm hover:border-white/40 hover:bg-white/30 hover:shadow-white/20'
                        : 'hover:bg-gray-100 hover:shadow-md'
                } ${isOpen ? 'ring-2 ring-green-500/50' : ''} ${className}`}
            >
                <UserAvatarAlternative
                    user={user}
                    size="md"
                    variant="initials"
                    className={`transition-all duration-300 ${isOpen ? 'ring-2 ring-green-400' : ''} group-hover:ring-2 group-hover:ring-green-300`}
                />
            </Button>

            {isOpen && (
                <div className="animate-in slide-in-from-top-2 absolute right-0 top-full z-50 mt-2 w-56 rounded-lg border bg-white shadow-xl backdrop-blur-sm duration-200">
                    {/* User Info */}
                    <div className="rounded-t-lg border-b bg-gradient-to-r from-green-50 to-blue-50 px-3 py-3">
                        <div className="group flex items-center gap-3">
                            <UserAvatarAlternative
                                user={user}
                                size="lg"
                                variant="initials"
                                className="ring-2 ring-green-200 transition-all duration-300 group-hover:ring-green-400"
                            />
                            <div className="flex-1 text-left">
                                <div className="font-semibold text-gray-900 transition-colors duration-300 group-hover:text-green-700">
                                    {user.name}
                                </div>
                                <div className="text-xs text-gray-600 transition-colors duration-300 group-hover:text-green-600">{user.email}</div>
                            </div>
                        </div>
                    </div>

                    {/* Menu Items */}
                    <div className="py-2">
                        <button
                            onClick={handleAdminClick}
                            className="group flex w-full items-center px-4 py-3 text-sm text-gray-700 transition-all duration-200 hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 hover:pl-6 hover:text-green-800 hover:shadow-sm"
                        >
                            <LayoutDashboard className="mr-3 h-4 w-4 transition-all duration-200 group-hover:scale-110 group-hover:text-green-600" />
                            <span className="font-medium">Admin Dashboard</span>
                        </button>
                        <button
                            onClick={handleLogout}
                            disabled={isLoading}
                            className="group flex w-full items-center rounded-b-lg px-4 py-3 text-sm text-gray-700 transition-all duration-200 hover:bg-gradient-to-r hover:from-red-50 hover:to-pink-50 hover:pl-6 hover:text-red-800 hover:shadow-sm disabled:cursor-not-allowed disabled:opacity-50"
                        >
                            {isLoading ? (
                                <div className="mr-3 h-4 w-4 animate-spin rounded-full border-2 border-red-600 border-t-transparent" />
                            ) : (
                                <LogOut className="mr-3 h-4 w-4 transition-all duration-200 group-hover:-translate-x-1 group-hover:scale-110 group-hover:text-red-600" />
                            )}
                            <span className="font-medium">{isLoading ? 'Logging out...' : 'Log out'}</span>
                        </button>
                    </div>
                </div>
            )}
        </div>
    );
}
