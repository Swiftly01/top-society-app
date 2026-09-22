import type { ChronicleEntry } from '@/types/about';

interface ChronicleTimelineProps {
    eyebrow: string;
    heading: string;
    sublabel: string;
    entries: ChronicleEntry[];
}

export function ChronicleTimeline({ eyebrow, heading, sublabel, entries }: ChronicleTimelineProps) {
    return (
        <section className="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div className="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <span className="text-[11px] font-semibold tracking-widest text-red-600 uppercase">{eyebrow}</span>
                    <h2 className="mt-1 font-serif text-3xl font-bold">{heading}</h2>
                </div>
                <span className="text-[11px] tracking-wide text-muted-foreground uppercase">{sublabel}</span>
            </div>

            <div className="mt-8 grid gap-8 border-t border-border pt-8 sm:grid-cols-4">
                {entries.map((entry) => (
                    <div key={entry.year} className="border-l-2 border-red-600 pl-4">
                        <p className="font-serif text-2xl font-bold">{entry.year}</p>
                        <p className="mt-1 text-[11px] font-semibold tracking-widest text-red-600 uppercase">
                            {entry.tag}
                        </p>
                        <h3 className="mt-2 text-sm font-bold">{entry.title}</h3>
                        <p className="mt-1 text-sm text-muted-foreground">{entry.description}</p>
                    </div>
                ))}
            </div>
        </section>
    );
}
