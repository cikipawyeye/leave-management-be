import Heading from '@/components/heading';
import PaginationComponent from '@/components/pagination';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Tabs, TabsList, TabsTrigger } from '@/components/ui/tabs';
import AppLayout from '@/layouts/app-layout';
import { debounce, ellipsisText, toTitleCase } from '@/lib/helpers';
import { Pagination, Permit, type BreadcrumbItem } from '@/types';
import { Deferred, Head, Link, router } from '@inertiajs/react';
import { Eye } from 'lucide-react';
import { useEffect, useMemo, useRef, useState } from 'react';
import CreatePermit from './partials/create';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Permits',
        href: route('permits.index'),
    },
];

function reloadData({ tab, search, sort }: { tab?: string; search?: string; sort?: string }) {
    return router.reload({
        data: {
            state: tab,
            search: search,
            sort: sort,
            page: 1,
        },
    });
}

export default function Index({ criteria, data }: Readonly<{ criteria: Record<string, string | number | null>; data?: Pagination<Permit> }>) {
    const [search, setSearch] = useState<string>(criteria.search as string);
    const [selectedTab, setSelectedTab] = useState((criteria.state as string) ?? 'all');
    const [sort, setSort] = useState((criteria.sort as string) ?? 'latest');

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
            <Head title="Permit" />

            <div className="px-4 py-6">
                <Heading title="Permit" description="Browse permit list" />

                <div className="flex flex-wrap items-center justify-between gap-4">
                    <div className="flex gap-4">
                        {<CreatePermit />}

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
                            <TabsTrigger onClick={() => setSelectedTab('pending')} value="pending">
                                Pending
                            </TabsTrigger>
                            <TabsTrigger onClick={() => setSelectedTab('revision')} value="revision">
                                Revision
                            </TabsTrigger>
                            <TabsTrigger onClick={() => setSelectedTab('approved')} value="approved">
                                Approved
                            </TabsTrigger>
                            <TabsTrigger onClick={() => setSelectedTab('rejected')} value="rejected">
                                Rejected
                            </TabsTrigger>
                        </TabsList>
                    </Tabs>
                </div>

                <div className="mt-7 overflow-x-auto">
                    <Deferred data="data" fallback={<div>Loading...</div>}>
                        <div>
                            <Table>
                                {data && (
                                    <TableCaption>
                                        <PaginationComponent data={data} />
                                    </TableCaption>
                                )}

                                <TableHeader>
                                    <TableRow>
                                        <TableHead className="">User</TableHead>
                                        <TableHead className="">Title</TableHead>
                                        <TableHead>Content</TableHead>
                                        <TableHead>Status</TableHead>
                                        <TableHead className="text-right"></TableHead>
                                    </TableRow>
                                </TableHeader>
                                <TableBody>
                                    {data?.data?.map((permit) => (
                                        <TableRow key={permit.id}>
                                            <TableCell className="font-medium">{permit.user?.name}</TableCell>
                                            <TableCell>{permit.title}</TableCell>
                                            <TableCell>{ellipsisText(permit.content)}</TableCell>
                                            <TableCell>{permit.state_label ?? toTitleCase(permit.state ?? '')}</TableCell>
                                            <TableCell className="text-right">
                                                <Link href={route('permits.show', { permit: permit.id })}>
                                                    <Button variant={'link'}>
                                                        <Eye /> Detail
                                                    </Button>
                                                </Link>
                                            </TableCell>
                                        </TableRow>
                                    ))}
                                    {data?.data.length === 0 && (
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
