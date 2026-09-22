import { cn } from '@/lib/utils';
import type { LegalBlock } from '@/types/legal';

interface LegalBlockRendererProps {
    block: LegalBlock;
}

export function LegalBlockRenderer({ block }: LegalBlockRendererProps) {
    if (block.type === 'paragraph') {
        return (
            <p
                className="text-sm leading-relaxed text-muted-foreground"
                // eslint-disable-next-line react/no-danger
                dangerouslySetInnerHTML={{ __html: block.html }}
            />
        );
    }

    if (block.type === 'list') {
        return (
            <ul className="flex flex-col gap-1.5 text-sm text-muted-foreground">
                {block.items.map((item, i) => (
                    <li key={i} className="flex gap-2">
                        <span className="mt-1 size-1 shrink-0 rounded-full bg-red-600" />
                        {item}
                    </li>
                ))}
            </ul>
        );
    }

    // notice
    return (
        <div
            className={cn(
                'rounded-sm border p-5',
                block.tone === 'dark' ? 'border-neutral-900 bg-neutral-950 text-white' : 'border-border bg-muted/40',
            )}
        >
            {block.label && (
                <p
                    className={cn(
                        'text-[10px] font-semibold tracking-widest uppercase',
                        block.tone === 'dark' ? 'text-red-500' : 'text-red-600',
                    )}
                >
                    {block.label}
                </p>
            )}
            {block.title && <h4 className="mt-1 font-serif text-base font-bold">{block.title}</h4>}
            {block.body && (
                <p className={cn('mt-2 text-sm', block.tone === 'dark' ? 'text-neutral-400' : 'text-muted-foreground')}>
                    {block.body}
                </p>
            )}
            {block.columns && (
                <div
                    className={cn(
                        'mt-4 grid gap-4 border-t pt-4 sm:grid-cols-3',
                        block.tone === 'dark' ? 'border-neutral-800' : 'border-border',
                    )}
                >
                    {block.columns.map((column) => (
                        <div key={column.title}>
                            <p className="text-xs font-semibold tracking-wide uppercase">{column.title}</p>
                            <p className={cn('mt-1 text-xs', block.tone === 'dark' ? 'text-neutral-400' : 'text-muted-foreground')}>
                                {column.body}
                            </p>
                        </div>
                    ))}
                </div>
            )}
        </div>
    );
}
