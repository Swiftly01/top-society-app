import type { AboutPillar } from '@/types/about';

interface PillarsGridProps {
    eyebrow: string;
    heading: string;
    description: string;
    items: AboutPillar[];
}

export function PillarsGrid({ eyebrow, heading, description, items }: PillarsGridProps) {
    return (
        <section className="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div className="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <span className="text-[11px] font-semibold tracking-widest text-red-600 uppercase">{eyebrow}</span>
                    <h2 className="mt-1 font-serif text-3xl font-bold">{heading}</h2>
                </div>
                <p className="max-w-sm text-sm text-muted-foreground">{description}</p>
            </div>

            <div className="mt-8 grid gap-6 sm:grid-cols-3">
                {items.map((pillar) => (
                    <div key={pillar.number} className="flex flex-col rounded-sm border border-border p-6">
                        <div className="flex items-center justify-between">
                            <span className="font-serif text-3xl font-bold text-red-600">{pillar.number}</span>
                            <span className="text-[10px] font-semibold tracking-widest text-muted-foreground uppercase">
                                {pillar.tag}
                            </span>
                        </div>
                        <h3 className="mt-4 font-serif text-xl font-bold">{pillar.title}</h3>
                        <p className="mt-2 flex-1 text-sm text-muted-foreground">{pillar.description}</p>
                        <p className="mt-4 border-t border-border pt-3 text-[11px] tracking-wide text-muted-foreground uppercase">
                            {pillar.footerNote}
                        </p>
                    </div>
                ))}
            </div>
        </section>
    );
}
