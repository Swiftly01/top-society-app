import { Link } from '@inertiajs/react';
import { Headphones, PlayCircle } from 'lucide-react';
import type { ReactNode } from 'react';
import { Highlight } from '@/components/search/highlight';
import { ArticleMedia } from '@/components/site/article-media';
import type { SearchResult } from '@/types/search';

interface SearchResultRowProps {
    result: SearchResult;
    query: string;
}

const typeIcon: Record<SearchResult['type'], ReactNode> = {
    Video: <PlayCircle className="size-4" />,
    Podcast: <Headphones className="size-4" />,
    Analysis: null,
    Article: null,
};

export function SearchResultRow({ result, query }: SearchResultRowProps) {
    return (
        <Link href={result.href} className="group grid grid-cols-[140px_1fr] gap-5 border-b border-border py-6 sm:grid-cols-[220px_1fr]">
            <div className="relative">
                <ArticleMedia src={result.image} alt={result.title} className="aspect-[4/3] w-full rounded-sm" />
                {typeIcon[result.type] && (
                    <span className="absolute right-2 bottom-2 rounded-full bg-black/80 p-1.5 text-white">
                        {typeIcon[result.type]}
                    </span>
                )}
            </div>
            <div className="flex flex-col gap-1.5">
                <div className="flex items-center gap-2 text-xs">
                    <span className="font-semibold tracking-wide text-red-600 uppercase">{result.type}</span>
                    <span className="text-muted-foreground">{result.date}</span>
                </div>
                <h2 className="font-serif text-xl leading-snug font-bold text-foreground group-hover:underline">
                    <Highlight text={result.title} query={query} />
                </h2>
                <p className="line-clamp-2 text-sm text-muted-foreground">
                    <Highlight text={result.excerpt} query={query} />
                </p>
                <span className="mt-1 text-sm font-medium text-foreground">{result.byline}</span>
            </div>
        </Link>
    );
}
