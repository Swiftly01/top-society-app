import { cn } from '@/lib/utils';
import type { OfficeLocation } from '@/types/contact';

interface OfficeNetworkGridProps {
    eyebrow: string;
    heading: string;
    sublabel: string;
    items: OfficeLocation[];
}

export function OfficeNetworkGrid({ eyebrow, heading, sublabel, items }: OfficeNetworkGridProps) {
    return (
        <section className="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div className="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <span className="text-[11px] font-semibold tracking-widest text-red-600 uppercase">{eyebrow}</span>
                    <h2 className="mt-1 font-serif text-3xl font-bold">{heading}</h2>
                </div>
                <span className="text-[11px] tracking-wide text-muted-foreground uppercase">{sublabel}</span>
            </div>

            <div className="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                {items.map((office) => (
                    <div
                        key={office.id}
                        className={cn(
                            'flex flex-col border-t-2 p-5',
                            office.featured ? 'border-red-600 bg-muted/40' : 'border-border',
                        )}
                    >
                        <div className="flex items-center justify-between">
                            <span className="text-[10px] font-semibold tracking-widest text-red-600 uppercase">
                                {office.tag}
                            </span>
                            <span className="text-[10px] text-muted-foreground">{office.timezoneLabel}</span>
                        </div>
                        <h3 className="mt-2 font-serif text-xl font-bold">{office.city}</h3>
                        <p className="text-xs font-medium tracking-wide text-muted-foreground uppercase">{office.region}</p>
                        <p className="mt-3 flex-1 text-sm text-muted-foreground">{office.address}</p>
                        <div className="mt-4 border-t border-border pt-3 text-xs">
                            <p className="font-semibold">{office.chiefName}</p>
                            <p className="text-muted-foreground">{office.chiefContact}</p>
                        </div>
                    </div>
                ))}
            </div>
        </section>
    );
}
