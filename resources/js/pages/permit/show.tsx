import Heading from '@/components/heading';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { formatLocaleDate, toTitleCase } from '@/lib/helpers';
import { Permit, type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';
import EditPermit from './partials/edit';

export default function Dashboard({ data }: Readonly<{ data: Permit }>) {
    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Dashboard',
            href: '/dashboard',
        },
        {
            title: 'Permits',
            href: route('permits.index'),
        },
        {
            title: `#${data.id}`,
            href: route('permits.show', { permit: data.id }),
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="px-4 py-6">
                <Heading title="Permit Detail" description="View permit detail" />

                <div className="mt-7 overflow-x-auto">
                    <Card>
                        <CardHeader>
                            <div className="flex flex-wrap justify-between gap-4">
                                <div>
                                    <CardTitle>{data.title}</CardTitle>
                                    <CardDescription>{data.user?.name}</CardDescription>
                                </div>

                                <div className="flex justify-end gap-2">
                                    <EditPermit permit={data} />
                                    {/* <ResetUserPassword user={user} /> */}
                                </div>
                            </div>
                        </CardHeader>
                        <CardContent>
                            <div className="grid grid-cols-1 sm:grid-cols-2">
                                <div>
                                    <div className="mb-2">
                                        <small>User</small>
                                        <p>{data.user?.name}</p>
                                    </div>
                                    <div className="mb-2">
                                        <small>Status</small>
                                        <p>{data.state_label ?? toTitleCase(data.state ?? '-')}</p>
                                    </div>
                                </div>
                                <div>
                                    <div className="mb-2">
                                        <small>Title</small>
                                        <p>{data.title}</p>
                                    </div>
                                    <div className="mb-2">
                                        <small>Date</small>
                                        <p>{data.created_at ? formatLocaleDate(data.created_at) : '-'}</p>
                                    </div>
                                </div>
                                <div className='sm:col-span-2'>
                                    <div className="mb-2">
                                        <blockquote className="mt-6 border-l-2 pl-6 italic">
                                            {data.content?.split('\n').map((line, index) => (
                                                <span key={index * 2}>
                                                    {line}
                                                    <br />
                                                </span>
                                            ))}
                                        </blockquote>
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
