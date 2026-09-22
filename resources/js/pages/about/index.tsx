import { Head, Link } from '@inertiajs/react';
import { ChronicleTimeline } from '@/components/about/chronicle-timeline';
import { PillarsGrid } from '@/components/about/pillars-grid';
import { StatsBand } from '@/components/about/stats-band';
import { TeamSlider } from '@/components/about/team-slider';
import { SiteFooter } from '@/components/site/site-footer';
import { SiteHeader } from '@/components/site/site-header';
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from '@/components/ui/breadcrumb';
import type { AboutPageProps } from '@/types/about';

export default function AboutIndex(props: AboutPageProps) {
    const { eyebrow, breadcrumbLabel, headingLead, headingEmphasis, quote, manifesto, dossier, stats, pillars, team, chronicle } =
        props;

    return (
        <>
            <Head title="About Us" />

            <div className="min-h-screen bg-background text-foreground">
                <SiteHeader activeNav="Home" />

                <main>
                    <section className="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                        <div className="flex flex-col gap-2 border-b border-border pb-4 sm:flex-row sm:items-center sm:justify-between">
                            <span className="text-[11px] font-semibold tracking-widest text-red-600 uppercase">
                                {eyebrow}
                            </span>
                            <Breadcrumb>
                                <BreadcrumbList>
                                    <BreadcrumbItem>
                                        <Link href="/" className="text-[11px] tracking-widest text-muted-foreground uppercase hover:text-foreground">
                                            Home
                                        </Link>
                                    </BreadcrumbItem>
                                    <BreadcrumbSeparator />
                                    <BreadcrumbItem>
                                        <BreadcrumbPage className="text-[11px] tracking-widest uppercase">
                                            {breadcrumbLabel}
                                        </BreadcrumbPage>
                                    </BreadcrumbItem>
                                </BreadcrumbList>
                            </Breadcrumb>
                        </div>

                        <div className="mt-8 grid gap-10 lg:grid-cols-[1fr_320px]">
                            <div>
                                <h1 className="font-serif text-4xl leading-tight font-bold sm:text-5xl">
                                    {headingLead} <em className="not-italic underline decoration-red-600 decoration-4 underline-offset-4">{headingEmphasis}</em>
                                </h1>

                                <blockquote className="mt-6 max-w-2xl border-l-4 border-red-600 py-1 pl-5 font-serif text-xl leading-snug italic">
                                    &quot;{quote.text}&quot;
                                    <footer className="mt-2 text-sm font-sans font-medium text-muted-foreground not-italic">
                                        — {quote.attribution}
                                    </footer>
                                </blockquote>

                                <div className="mt-10 grid gap-8 border-t border-border pt-8 sm:grid-cols-2">
                                    {manifesto.map((item) => (
                                        <div key={item.heading}>
                                            <h2 className="text-xs font-semibold tracking-widest text-red-600 uppercase">
                                                {item.heading}
                                            </h2>
                                            <p className="mt-2 text-sm leading-relaxed text-muted-foreground">{item.body}</p>
                                        </div>
                                    ))}
                                </div>
                            </div>

                            {/* Institutional dossier box */}
                            <aside className="h-fit rounded-sm bg-neutral-950 p-5 text-white">
                                <span className="text-[10px] font-semibold tracking-widest text-red-500 uppercase">
                                    {dossier.label}
                                </span>
                                <h3 className="mt-2 font-serif text-lg font-bold">{dossier.title}</h3>
                                <p className="mt-2 text-sm text-neutral-400">{dossier.body}</p>
                                <dl className="mt-4 flex flex-col gap-1.5 border-t border-neutral-800 pt-4">
                                    {dossier.meta.map((item) => (
                                        <div key={item.label} className="flex items-center justify-between text-[11px]">
                                            <dt className="tracking-wide text-neutral-500 uppercase">{item.label}</dt>
                                            <dd className="font-semibold text-red-500 uppercase">{item.value}</dd>
                                        </div>
                                    ))}
                                </dl>
                            </aside>
                        </div>
                    </section>

                    <StatsBand
                        eyebrow={stats.eyebrow}
                        heading={stats.heading}
                        sublabel={stats.sublabel}
                        items={stats.items}
                    />

                    <PillarsGrid
                        eyebrow={pillars.eyebrow}
                        heading={pillars.heading}
                        description={pillars.description}
                        items={pillars.items}
                    />

                  <TeamSlider eyebrow={team.eyebrow} heading={team.heading} sublabel={team.sublabel} members={team.members} />
                  
                    <ChronicleTimeline
                        eyebrow={chronicle.eyebrow}
                        heading={chronicle.heading}
                        sublabel={chronicle.sublabel}
                        entries={chronicle.entries}
                    />
                </main>

                <SiteFooter />
            </div>
        </>
    );
}
