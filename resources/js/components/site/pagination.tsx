import { router } from '@inertiajs/react';
import { cn } from '@/lib/utils';

interface PaginationProps {
    currentPage: number;
    lastPage: number;
    /** Extra query params to keep (e.g. the current search query/category). */
    preserveParams?: Record<string, string>;
}

function pageList(current: number, last: number): (number | 'ellipsis')[] {
    if (last <= 7) return Array.from({ length: last }, (_, i) => i + 1);

    const pages = new Set([1, 2, last - 1, last, current - 1, current, current + 1]);
    const sorted = [...pages].filter((p) => p >= 1 && p <= last).sort((a, b) => a - b);

    const withEllipsis: (number | 'ellipsis')[] = [];
    sorted.forEach((page, i) => {
        if (i > 0 && page - sorted[i - 1] > 1) withEllipsis.push('ellipsis');
        withEllipsis.push(page);
    });
    return withEllipsis;
}

export function Pagination({ currentPage, lastPage, preserveParams = {} }: PaginationProps) {
    function goTo(page: number) {
        router.get(
            window.location.pathname,
            { ...preserveParams, page },
            { preserveScroll: true, preserveState: true },
        );
    }

    return (
        <nav className="flex items-center justify-center gap-2" aria-label="Pagination">
            <button
                type="button"
                disabled={currentPage <= 1}
                onClick={() => goTo(currentPage - 1)}
                className="rounded-md border border-border px-3 py-1.5 text-sm font-medium disabled:cursor-not-allowed disabled:opacity-40"
            >
                Previous
            </button>

            {pageList(currentPage, lastPage).map((page, i) =>
                page === 'ellipsis' ? (
                    <span key={`ellipsis-${i}`} className="px-1 text-sm text-muted-foreground">
                        …
                    </span>
                ) : (
                    <button
                        key={page}
                        type="button"
                        onClick={() => goTo(page)}
                        className={cn(
                            'size-8 rounded-md text-sm font-medium',
                            page === currentPage ? 'bg-red-600 text-white' : 'text-foreground hover:bg-accent',
                        )}
                    >
                        {page}
                    </button>
                ),
            )}

            <button
                type="button"
                disabled={currentPage >= lastPage}
                onClick={() => goTo(currentPage + 1)}
                className="rounded-md border border-border px-3 py-1.5 text-sm font-medium disabled:cursor-not-allowed disabled:opacity-40"
            >
                Next
            </button>
        </nav>
    );
}
