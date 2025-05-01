import { LucideIcon } from 'lucide-react';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
    permissions: string[];
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavGroup {
    title: string;
    items: NavItem[];
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon | null;
    isActive?: boolean;
    permission?: string;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
    [key: string]: unknown;
}

export interface Pagination<T> {
    data: T[];
    links: {
        url: string | null;
        label: string | null;
        active: boolean;
    }[];
    meta: {
        current_page: number | null;
        first_page_url: string | null;
        from: number | null;
        last_page: number | null;
        last_page_url: string | null;
        next_page_url: string | null;
        path: string | null;
        per_page: number | null;
        prev_page_url: string | null;
        to: number | null;
        total: number | null;
    };
}

export interface User {
    id?: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at?: string | null;
    created_at?: string;
    updated_at?: string;
    role?: string;
}

export interface Permit {
    id?: number;
    title: string;
    content: string;
    state?: 'approved' | 'pending' | 'rejected' | 'revision';
    created_at?: string;
    state_label?: string;
    user?: User;
}
