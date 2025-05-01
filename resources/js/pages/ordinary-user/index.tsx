import Heading from '@/components/heading';
import PaginationComponent from '@/components/pagination';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Tabs, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AppLayout from '@/layouts/app-layout';
import { debounce, formatLocaleDate } from '@/lib/helpers';
import { Pagination, User, type BreadcrumbItem } from '@/types';
import { Deferred, Head, Link, router } from '@inertiajs/react';
import { Eye } from 'lucide-react';
import { useEffect, useMemo, useRef, useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Users',
        href: route('ordinary-users.index'),
    },
];

function reloadData({ tab, search, sort }: { tab?: string; search?: string; sort?: string }) {
    return router.reload({
        data: {
            verified: tab,
            search: search,
            sort: sort,
            page: 1,
        },
    });
}

export default function Index({ criteria, users }: Readonly<{ criteria: Record<string, string | number | null>; users?: Pagination<User> }>) {
    const [search, setSearch] = useState<string>(criteria.search as string);
    const [selectedTab, setSelectedTab] = useState((criteria.verified as string) ?? 'all');
    const [sort, setSort] = useState((criteria.sort as string) ?? 'name');

    const isFirstRender = useRef(true);

    useEffect(() => {
        if (isFirstRender.current) {
            isFirstRender.current = false;
            return;
        }

        reloadData({ tab: selectedTab, search, sort });
    }, [selectedTab, search, sort]);

    const debouncedSetSearch = useMemo(() => debounce((value: string) => setSearch(value), 300), []);

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Users" />

            <div className="px-4 py-6">
                <Heading title="Users" description="Browse user list" />

                <div className="flex flex-wrap items-center justify-between gap-4">
                    <div className="flex gap-4">
                        <Input
                            defaultValue={search ?? ''}
                            onChange={(el) => debouncedSetSearch(el.target.value)}
                            className="h-9"
                            type="search"
                            placeholder="Search..."
                        />

                        <Select onValueChange={(value) => setSort(value)} value={sort}>
                            <SelectTrigger className="h-9">
                                <span className="text-muted-foreground">Sort by: </span>
                                <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="name"> Name </SelectItem>
                                <SelectItem value="latest"> Latest </SelectItem>
                                <SelectItem value="oldest"> Oldest </SelectItem>
                            </SelectContent>
                        </Select>
                    </div>

                    <Tabs defaultValue={selectedTab}>
                        <TabsList>
                            <TabsTrigger onClick={() => setSelectedTab('all')} value="all">
                                All Status
                            </TabsTrigger>
                            <TabsTrigger onClick={() => setSelectedTab('verified')} value="verified">
                                Verified
                            </TabsTrigger>
                            <TabsTrigger onClick={() => setSelectedTab('unverified')} value="unverified">
                                Unverified
                            </TabsTrigger>
                        </TabsList>
                    </Tabs>
                </div>

                <div className="mt-7 overflow-x-auto">
                    <Deferred data="users" fallback={<div>Loading...</div>}>
                        <div>
                            <Table>
                                {users && (
                                    <TableCaption>
                                        <PaginationComponent data={users} />
                                    </TableCaption>
                                )}

                                <TableHeader>
                                    <TableRow>
                                        <TableHead className="">Name</TableHead>
                                        <TableHead className="">Email</TableHead>
                                        <TableHead>Registered at</TableHead>
                                        <TableHead>Status</TableHead>
                                        <TableHead className="text-right"></TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {users?.data?.map((user) => (
                                        <TableRow key={user.id}>
                                            <TableCell className="font-medium">{user.name}</TableCell>
                                            <TableCell>{user.email}</TableCell>
                                            <TableCell>{formatLocaleDate(user.created_at)}</TableCell>
                                            <TableCell>{user.email_verified_at ? 'Verified' : 'Unverified'}</TableCell>
                                            <TableCell className="text-right">
                                                <Link href={route('ordinary-users.show', { ordinary_user: user.id })}>
                                                    <Button variant={'link'}>
                                                        <Eye /> Detail
                                                    </Button>
                                                </Link>
                                            </TableCell>
                                        </TableRow>
                                    ))}
                                    {users?.data.length === 0 && (
                                        <TableRow>
                                            <TableCell colSpan={5} className="py-12 text-center">
                                                No data found.
                                            </TableCell>
                                        </TableRow>
                                    )}
                                </TableBody>
                            </Table>
                        </div>
                    </Deferred>
                </div>
            </div>
        </AppLayout>
    );
}
