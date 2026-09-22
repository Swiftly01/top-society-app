import type { AboutStat } from '@/types/about';

interface StatsBandProps {
    eyebrow: string;
    heading: string;
    sublabel: string;
    items: AboutStat[];
}

export function StatsBand({ eyebrow, heading, sublabel, items }: StatsBandProps) {
    return (
        <section className="bg-neutral-950 text-white">
            <div className="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
                <div className="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <span className="text-[11px] font-semibold tracking-widest text-red-500 uppercase">
                            {eyebrow}
                        </span>
                        <h2 className="mt-1 font-serif text-2xl font-bold">{heading}</h2>
                    </div>
                    <span className="text-[11px] tracking-wide text-neutral-500 uppercase">{sublabel}</span>
                </div>

                <div className="mt-8 grid grid-cols-2 gap-6 border-t border-neutral-800 pt-8 sm:grid-cols-4">
                    {items.map((stat) => (
                        <div key={stat.label} className="border-l-2 border-red-600 pl-4">
                            <p className="font-serif text-4xl font-bold">{stat.value}</p>
                            <p className="mt-1 text-xs tracking-wide text-neutral-400 uppercase">{stat.label}</p>
                        </div>
                    ))}
                </div>
            </div>
        </section>
    );
}
