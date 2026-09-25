export interface ArticleSuggestion {
    id: number | string;
    title: string;
    category: string;
    image?: string | null;
    href: string;
}

export interface SimpleSuggestion {
    id: number | string;
    label: string;
    href: string;
}

export interface SearchSuggestions {
    articles: ArticleSuggestion[];
    categories: SimpleSuggestion[];
    tags: SimpleSuggestion[];
}