import {
    Pagination as BasePagination,
    PaginationContent,
    PaginationEllipsis,
    PaginationItem,
    PaginationLink,
    PaginationNext,
    PaginationPrevious,
} from '@/components/ui/pagination';

import { Pagination as Meta } from '@/types';
import { Link } from '@inertiajs/react';

interface PaginationComponentProps<T> {
    data: Meta<T>;
}

export default function Pagination<T>({ data }: Readonly<PaginationComponentProps<T>>) {
    const { links } = data;
    const { from, to, total = 0, prev_page_url: prevPageUrl, next_page_url: nextPageUrl } = data.meta;

    const paginationItems = links
        .filter((link) => !link.label?.startsWith('Next') && !link.label?.endsWith('Previous'))
        .map((link) => ({
            type: link.label != '...' ? 'page' : 'ellipsis',
            label: link.label,
            url: link.url,
            active: link.active,
        }));

    const setUrlQueryParamPage = (page: number) => {
        if (page === null || isNaN(page)) {
            return;
        }

        const url = new URL(window.location.href);
        url.searchParams.set('page', page.toString());
        return url.toString();
    };

    return (
        <>
            {prevPageUrl || nextPageUrl ? (
                <div className="flex w-full flex-wrap items-center justify-between gap-4">
                    <p className="my-auto text-sm text-gray-700 dark:text-gray-400">
                        Showing <span className="font-medium">{from}</span> to <span className="font-medium">{to}</span> of &nbsp;
                        <span className="font-medium">{total}</span> results
                    </p>

                    <div className="my-auto">
                        <BasePagination>
                            <PaginationContent>
                                <PaginationItem>
                                    {prevPageUrl ? (
                                        <Link href={prevPageUrl}>
                                            <PaginationPrevious />
                                        </Link>
                                    ) : (
                                        <PaginationPrevious />
                                    )}
                                </PaginationItem>

                                {paginationItems.map((item, index) => (
                                    <PaginationItem key={index * 5}>
                                        {item.type === 'page' ? (
                                            <Link href={item.url!}>
                                                <PaginationLink onClick={() => setUrlQueryParamPage(+item.label!)} isActive={item.active}>
                                                    {item.label}
                                                </PaginationLink>
                                            </Link>
                                        ) : (
                                            <PaginationEllipsis />
                                        )}
                                    </PaginationItem>
                                ))}

                                <PaginationItem>
                                    {nextPageUrl ? (
                                        <Link href={nextPageUrl}>
                                            <PaginationNext />
                                        </Link>
                                    ) : (
                                        <PaginationNext />
                                    )}
                                </PaginationItem>
                            </PaginationContent>
                        </BasePagination>
                    </div>
                </div>
            ) : (
                <div className="w-full">
                    <p className="text-start text-sm text-gray-700 dark:text-gray-400">
                        Showing <span className="font-medium">{total}</span> results
                    </p>
                </div>
            )}
        </>
    );
}
