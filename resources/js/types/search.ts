export interface SearchResult {
    id: number | string;
    type: 'Analysis' | 'Video' | 'Podcast' | 'Article';
    date: string;
    title: string;
    excerpt: string;
    byline: string;
    image?: string | null;
    href: string;
}

export interface SearchFilters {
    dateRange: { label: string; value: string }[];
    activeDateRange: string;
    sortOptions: { label: string; value: string }[];
    activeSort: string;
    contentTypes: { label: string; value: string; checked: boolean }[];
}

/** Page-specific prop contract for `search/index` (sitewide chrome comes from `SiteShared`). */
export interface SearchPageProps {
    query: string;
    activeSection: string;
    totalResults: number;
    resultsStart: number;
    resultsEnd: number;
    results: SearchResult[];
    filters: SearchFilters;
    currentPage: number;
    lastPage: number;
}
