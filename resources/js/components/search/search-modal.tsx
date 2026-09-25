import { useEffect, useRef, useState, type KeyboardEvent, type ReactNode } from 'react';
import { createPortal } from 'react-dom';
import { router } from '@inertiajs/react';
import { Loader2, Newspaper, Search, Tag as TagIcon, X } from 'lucide-react';
import { ArticleMedia } from '@/components/site/article-media';
import { useSearchSuggestions } from '@/hooks/use-search-suggestions';
import { cn } from '@/lib/utils';

interface SearchModalProps {
    open: boolean;
    onOpenChange: (open: boolean) => void;
}

interface FlatResult {
    key: string;
    href: string;
}

export function SearchModal({ open, onOpenChange }: SearchModalProps) {
    const [query, setQuery] = useState('');
    const [activeIndex, setActiveIndex] = useState(0);
    const inputRef = useRef<HTMLInputElement>(null);
    const { results, loading } = useSearchSuggestions(query);

    const flat: FlatResult[] = [
        ...results.articles.map((a) => ({ key: `article-${a.id}`, href: a.href })),
        ...results.categories.map((c) => ({ key: `category-${c.id}`, href: c.href })),
        ...results.tags.map((t) => ({ key: `tag-${t.id}`, href: t.href })),
    ];

    useEffect(() => {
        if (open) {
            setQuery('');
            setActiveIndex(0);
            document.body.style.overflow = 'hidden';
            requestAnimationFrame(() => inputRef.current?.focus());
        } else {
            document.body.style.overflow = '';
        }
        return () => {
            document.body.style.overflow = '';
        };
    }, [open]);

    useEffect(() => setActiveIndex(0), [query]);

    // Global ⌘K / Ctrl+K toggle — works regardless of which header variant
    // (masthead vs compact) is mounted on the current page.
    useEffect(() => {
        function handler(e: globalThis.KeyboardEvent) {
            if ((e.metaKey || e.ctrlKey) && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                onOpenChange(!open);
            }
        }
        window.addEventListener('keydown', handler);
        return () => window.removeEventListener('keydown', handler);
    }, [open, onOpenChange]);

    function goTo(href: string) {
        onOpenChange(false);
        router.get(href);
    }

    function handleKeyDown(e: KeyboardEvent<HTMLInputElement>) {
        if (e.key === 'Escape') {
            onOpenChange(false);
        } else if (e.key === 'ArrowDown') {
            e.preventDefault();
            setActiveIndex((i) => Math.min(i + 1, flat.length - 1));
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            setActiveIndex((i) => Math.max(i - 1, 0));
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (flat[activeIndex]) {
                goTo(flat[activeIndex].href);
            } else if (query.trim().length >= 2) {
                goTo(`/search?q=${encodeURIComponent(query.trim())}`);
            }
        }
    }

    if (!open) return null;

    return createPortal(
        <div
            className="fixed inset-0 z-50 flex items-start justify-center bg-black/60 px-4 pt-[10vh]"
            onClick={() => onOpenChange(false)}
        >
            <div
                role="dialog"
                aria-modal="true"
                aria-label="Search"
                onClick={(e) => e.stopPropagation()}
                className="flex max-h-[70vh] w-full max-w-xl flex-col overflow-hidden rounded-lg bg-background shadow-2xl"
            >
                <div className="flex items-center gap-3 border-b border-border px-4 py-3">
                    <Search className="size-5 shrink-0 text-muted-foreground" />
                    <input
                        ref={inputRef}
                        value={query}
                        onChange={(e) => setQuery(e.target.value)}
                        onKeyDown={handleKeyDown}
                        placeholder="Search articles, categories, tags…"
                        className="w-full bg-transparent text-base outline-none placeholder:text-muted-foreground"
                    />
                    {loading && <Loader2 className="size-4 shrink-0 animate-spin text-muted-foreground" />}
                    <button
                        type="button"
                        onClick={() => onOpenChange(false)}
                        aria-label="Close search"
                        className="shrink-0 rounded-md p-1 text-muted-foreground hover:bg-accent hover:text-foreground"
                    >
                        <X className="size-4" />
                    </button>
                </div>

                <div className="flex-1 overflow-y-auto">
                    {query.trim().length < 2 && (
                        <p className="px-4 py-8 text-center text-sm text-muted-foreground">
                            Type at least 2 characters to search.
                        </p>
                    )}

                    {query.trim().length >= 2 && !loading && flat.length === 0 && (
                        <p className="px-4 py-8 text-center text-sm text-muted-foreground">
                            No results for &quot;{query}&quot;.
                        </p>
                    )}

                    {results.articles.length > 0 && (
                        <ResultGroup label="Articles">
                            {results.articles.map((article) => {
                                const index = flat.findIndex((f) => f.key === `article-${article.id}`);
                                return (
                                    <button
                                        key={article.id}
                                        type="button"
                                        onMouseEnter={() => setActiveIndex(index)}
                                        onClick={() => goTo(article.href)}
                                        className={cn(
                                            'flex w-full items-center gap-3 px-4 py-2 text-left',
                                            index === activeIndex ? 'bg-accent' : 'hover:bg-accent/50',
                                        )}
                                    >
                                        <ArticleMedia
                                            src={article.image}
                                            alt={article.title}
                                            className="size-10 shrink-0 rounded-sm"
                                        />
                                        <span className="min-w-0">
                                            <span className="block truncate text-sm font-medium text-foreground">
                                                {article.title}
                                            </span>
                                            <span className="text-xs text-muted-foreground">{article.category}</span>
                                        </span>
                                    </button>
                                );
                            })}
                        </ResultGroup>
                    )}

                    {results.categories.length > 0 && (
                        <ResultGroup label="Categories">
                            {results.categories.map((category) => {
                                const index = flat.findIndex((f) => f.key === `category-${category.id}`);
                                return (
                                    <button
                                        key={category.id}
                                        type="button"
                                        onMouseEnter={() => setActiveIndex(index)}
                                        onClick={() => goTo(category.href)}
                                        className={cn(
                                            'flex w-full items-center gap-3 px-4 py-2 text-left text-sm',
                                            index === activeIndex ? 'bg-accent' : 'hover:bg-accent/50',
                                        )}
                                    >
                                        <Newspaper className="size-4 shrink-0 text-muted-foreground" />
                                        {category.label}
                                    </button>
                                );
                            })}
                        </ResultGroup>
                    )}

                    {results.tags.length > 0 && (
                        <ResultGroup label="Tags">
                            {results.tags.map((tag) => {
                                const index = flat.findIndex((f) => f.key === `tag-${tag.id}`);
                                return (
                                    <button
                                        key={tag.id}
                                        type="button"
                                        onMouseEnter={() => setActiveIndex(index)}
                                        onClick={() => goTo(tag.href)}
                                        className={cn(
                                            'flex w-full items-center gap-3 px-4 py-2 text-left text-sm',
                                            index === activeIndex ? 'bg-accent' : 'hover:bg-accent/50',
                                        )}
                                    >
                                        <TagIcon className="size-4 shrink-0 text-muted-foreground" />
                                        {tag.label}
                                    </button>
                                );
                            })}
                        </ResultGroup>
                    )}
                </div>

                {query.trim().length >= 2 && (
                    <button
                        type="button"
                        onClick={() => goTo(`/search?q=${encodeURIComponent(query.trim())}`)}
                        className="border-t border-border px-4 py-3 text-left text-sm font-medium text-red-600 hover:bg-accent"
                    >
                        View all results for &quot;{query}&quot; →
                    </button>
                )}
            </div>
        </div>,
        document.body,
    );
}

function ResultGroup({ label, children }: { label: string; children: ReactNode }) {
    return (
        <div className="py-2">
            <p className="px-4 pb-1 text-[10px] font-semibold tracking-widest text-muted-foreground uppercase">
                {label}
            </p>
            {children}
        </div>
    );
}