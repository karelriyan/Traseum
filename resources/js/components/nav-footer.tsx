import { Icon } from '@/components/icon';
import { SidebarGroup, SidebarGroupContent, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { type ComponentPropsWithoutRef } from 'react';

export function NavFooter({
    items,
    className,
    ...props
}: ComponentPropsWithoutRef<typeof SidebarGroup> & {
    items: NavItem[];
}) {
    return (
        <SidebarGroup {...props} className={`group-data-[collapsible=icon]:p-0 ${className || ''}`}>
            <SidebarGroupContent>
                <SidebarMenu>
                    {items.map((item) => (
                        <SidebarMenuItem key={item.title}>
                            <SidebarMenuButton
                                asChild
                                className="group text-neutral-600 transition-all duration-300 hover:scale-105 hover:bg-neutral-100 hover:text-neutral-800 hover:shadow-sm"
                            >
                                <a
                                    href={item.href}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="flex items-center gap-3 rounded-md transition-all duration-300"
                                >
                                    {item.icon && (
                                        <Icon
                                            iconNode={item.icon}
                                            className="h-5 w-5 transition-all duration-300 group-hover:scale-110 group-hover:text-blue-600"
                                        />
                                    )}
                                    <span className="relative">
                                        {item.title}
                                        <span className="absolute bottom-0 left-0 h-0.5 w-0 bg-blue-600 transition-all duration-300 group-hover:w-full"></span>
                                    </span>
                                </a>
                            </SidebarMenuButton>
                        </SidebarMenuItem>
                    ))}
                </SidebarMenu>
            </SidebarGroupContent>
        </SidebarGroup>
    );
}
