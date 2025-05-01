import Heading from '@/components/heading';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { formatLocaleDate, toTitleCase } from '@/lib/helpers';
import { User, type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import Verify from './partials/verify';

export default function Dashboard({ user }: Readonly<{ user: User }>) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Dashboard',
            href: '/dashboard',
        },
        {
            title: 'Users',
            href: route('ordinary-users.index'),
        },
        {
            title: user?.name ?? 'Detail',
            href: route('ordinary-users.show', { ordinary_user: 1 }),
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={user.name} />

            <div className="px-4 py-6">
                <Heading title="User Detail" description="View user detail" />

                <div className="mt-7 overflow-x-auto">
                    <Card>
                        <CardHeader>
                            <div className="flex flex-wrap justify-between gap-4">
                                <div>
                                    <CardTitle>Account Detail</CardTitle>
                                    <CardDescription>{user.name}</CardDescription>
                                </div>

                                {user.role == 'user' && user.email_verified_at == null && (
                                    <div className="flex justify-end gap-2">
                                        <Verify user={user} />
                                    </div>
                                )}
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div className="grid grid-cols-1 sm:grid-cols-2">
                                <div>
                                    <div className="mb-2">
                                        <small>Name</small>
                                        <p>{user.name}</p>
                                    </div>
                                    <div className="mb-2">
                                        <small>Email</small>
                                        <p>{user.email}</p>
                                    </div>
                                    <div className="mb-2">
                                        <small>Role</small>
                                        <p>{toTitleCase(user.role ?? '-')}</p>
                                    </div>
                                </div>
                                <div>
                                    <div className="mb-2">
                                        <small>Registered at</small>
                                        <p>{user.created_at ? formatLocaleDate(user.created_at) : '-'}</p>
                                    </div>
                                    <div className="mb-2">
                                        <small>Verified at</small>
                                        <p>{user.email_verified_at ? formatLocaleDate(user.email_verified_at) : 'Not verified yet'}</p>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
}
