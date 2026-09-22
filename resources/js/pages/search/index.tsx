import { Head } from '@inertiajs/react';
import { SearchFiltersPanel } from '@/components/search/search-filters-panel';
import { SearchResultRow } from '@/components/search/search-result-row';
import { Pagination } from '@/components/site/pagination';
import { SectionHeader } from '@/components/site/section-header';
import { SiteFooter } from '@/components/site/site-footer';
import type { SearchPageProps } from '@/types/search';

export default function SearchIndex(props: SearchPageProps) {
    const { query, activeSection, totalResults, resultsStart, resultsEnd, results, filters, currentPage, lastPage } =
        props;

    return (
        <>
            <Head title={`Results for "${query}"`} />

            <div className="min-h-screen bg-background text-foreground">
                <SectionHeader activeNav={activeSection} />

                <main className="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
                    <h1 className="font-serif text-4xl font-bold">Results for &quot;{query}&quot;</h1>
                    <p className="mt-2 text-sm text-muted-foreground">
                        Showing {resultsStart}-{resultsEnd} of {totalResults} results
                    </p>

                    <div className="mt-8 grid gap-10 border-t border-border pt-8 lg:grid-cols-[240px_1fr]">
                        <SearchFiltersPanel filters={filters} query={query} />

                        <div>
                            <div className="flex flex-col">
                                {results.map((result) => (
                                    <SearchResultRow key={result.id} result={result} query={query} />
                                ))}
                            </div>

                            {results.length === 0 && (
                                <p className="py-16 text-center text-sm text-muted-foreground">
                                    No results found for &quot;{query}&quot;.
                                </p>
                            )}

                            {lastPage > 1 && (
                                <div className="mt-8">
                                    <Pagination currentPage={currentPage} lastPage={lastPage} preserveParams={{ q: query }} />
                                </div>
                            )}
                        </div>
                    </div>
                </main>

                <SiteFooter />
            </div>
        </>
    );
}
