import { Link } from '@inertiajs/react';
import { ArrowRight } from 'lucide-react';
import { cn } from '@/lib/utils';
import type { BureauDesk } from '@/types/contact';

interface BureauDeskGridProps {
    eyebrow: string;
    heading: string;
    description: string;
    items: BureauDesk[];
}

export function BureauDeskGrid({ eyebrow, heading, description, items }: BureauDeskGridProps) {
    return (
        <section className="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div className="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <span className="text-[11px] font-semibold tracking-widest text-red-600 uppercase">{eyebrow}</span>
                    <h2 className="mt-1 font-serif text-3xl font-bold">{heading}</h2>
                </div>
                <p className="max-w-sm text-sm text-muted-foreground">{description}</p>
            </div>

            <div className="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                {items.map((desk) => (
                    <div
                        key={desk.id}
                        className={cn(
                            'flex flex-col rounded-sm border p-6',
                            desk.featured
                                ? 'border-neutral-900 bg-neutral-950 text-white'
                                : 'border-border',
                        )}
                    >
                        <span
                            className={cn(
                                'text-[10px] font-semibold tracking-widest uppercase',
                                desk.featured ? 'text-red-500' : 'text-red-600',
                            )}
                        >
                            {desk.id}
                        </span>
                        <h3 className="mt-2 font-serif text-lg font-bold">{desk.title}</h3>
                        <p className={cn('mt-2 flex-1 text-sm', desk.featured ? 'text-neutral-400' : 'text-muted-foreground')}>
                            {desk.description}
                        </p>
                        <div className={cn('mt-4 flex flex-col gap-0.5 border-t pt-3 text-xs', desk.featured ? 'border-neutral-800' : 'border-border')}>
                            {desk.contactLines.map((line) => (
                                <span key={line} className={desk.featured ? 'text-neutral-300' : 'text-muted-foreground'}>
                                    {line}
                                </span>
                            ))}
                        </div>
                        <Link
                            href={desk.ctaHref}
                            className={cn(
                                'mt-4 inline-flex items-center gap-1 text-xs font-semibold tracking-wide uppercase',
                                desk.featured ? 'text-red-500' : 'text-red-600',
                            )}
                        >
                            {desk.ctaLabel} <ArrowRight className="size-3" />
                        </Link>
                    </div>
                ))}
            </div>
        </section>
    );
}
