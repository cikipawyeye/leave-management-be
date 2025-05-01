import { SidebarProvider } from '@/components/ui/sidebar';
import { usePermission } from '@/contexts/permission-context';
import { SharedData } from '@/types';
import { usePage } from '@inertiajs/react';
import { useEffect } from 'react';

interface AppShellProps {
    children: React.ReactNode;
    variant?: 'header' | 'sidebar';
}

export function AppShell({ children, variant = 'header' }: Readonly<AppShellProps>) {
    const page = usePage<SharedData>();
    const isOpen = page.props.sidebarOpen;
    const {
        permissions,
        user: { role },
    } = page.props.auth;

    const { setRole, setPermissions } = usePermission();

    useEffect(() => {
        setRole(role);
        setPermissions(permissions ?? []);
    }, [role, setRole, permissions, setPermissions]);

    if (variant === 'header') {
        return <div className="flex min-h-screen w-full flex-col">{children}</div>;
    }

    return <SidebarProvider defaultOpen={isOpen}>{children}</SidebarProvider>;
}
