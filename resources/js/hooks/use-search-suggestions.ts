import { useEffect, useRef, useState } from 'react';
import type { SearchSuggestions } from '@/types/search-suggestions';

const EMPTY: SearchSuggestions = { articles: [], categories: [], tags: [] };

export function useSearchSuggestions(query: string) {
    const [results, setResults] = useState<SearchSuggestions>(EMPTY);
    const [loading, setLoading] = useState(false);
    const abortRef = useRef<AbortController | null>(null);

    useEffect(() => {
        const trimmed = query.trim();

        if (trimmed.length < 2) {
            abortRef.current?.abort();
            setResults(EMPTY);
            setLoading(false);
            return;
        }

        const timeout = setTimeout(() => {
            abortRef.current?.abort();
            const controller = new AbortController();
            abortRef.current = controller;
            setLoading(true);

            fetch(`/api/search/suggest?q=${encodeURIComponent(trimmed)}`, {
                signal: controller.signal,
                headers: { Accept: 'application/json' },
            })
                .then((res) => (res.ok ? (res.json() as Promise<SearchSuggestions>) : EMPTY))
                .then((data) => setResults(data))
                .catch((error: unknown) => {
                    if (error instanceof DOMException && error.name === 'AbortError') return;
                    setResults(EMPTY);
                })
                .finally(() => setLoading(false));
        }, 250);

        return () => clearTimeout(timeout);
    }, [query]);

    return { results, loading };
}