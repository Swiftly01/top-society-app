import { Fragment } from 'react';

interface HighlightProps {
    text: string;
    query: string;
}

/** Wraps case-insensitive matches of `query` in a highlighted <mark>. */
export function Highlight({ text, query }: HighlightProps) {
    if (!query.trim()) return <>{text}</>;

    const escaped = query.trim().replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    const parts = text.split(new RegExp(`(${escaped})`, 'gi'));

    return (
        <>
            {parts.map((part, i) =>
                part.toLowerCase() === query.trim().toLowerCase() ? (
                    <mark key={i} className="rounded-sm bg-blue-100 text-inherit dark:bg-blue-500/30">
                        {part}
                    </mark>
                ) : (
                    <Fragment key={i}>{part}</Fragment>
                ),
            )}
        </>
    );
}
