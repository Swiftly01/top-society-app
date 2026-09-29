import type { AboutContentOffering } from '@/types/about';

interface ContentOfferingsGridProps {
    eyebrow: string;
    heading: string;
    description: string;
    items: AboutContentOffering[];
}

export function ContentOfferingsGrid({ eyebrow, heading, description, items }: ContentOfferingsGridProps) {
    return (
        <section className="border-t border-border bg-muted/30">
            <div className="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
                <div className="flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <span className="text-[11px] font-semibold tracking-widest text-red-600 uppercase">
                            {eyebrow}
                        </span>
                        <h2 className="mt-1 font-serif text-3xl font-bold">{heading}</h2>
                    </div>
                    <p className="max-w-sm text-sm text-muted-foreground">{description}</p>
                </div>

                <div className="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                    {items.map((item) => (
                        <div key={item.number} className="flex gap-4 rounded-sm border border-border bg-background p-5">
                            <span className="font-serif text-2xl font-bold text-red-600">{item.number}</span>
                            <div>
                                <h3 className="font-serif text-base font-bold">{item.title}</h3>
                                <p className="mt-1.5 text-sm text-muted-foreground">{item.description}</p>
                            </div>
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
}
