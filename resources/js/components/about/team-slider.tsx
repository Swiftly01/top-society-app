import { useRef } from 'react';
import { Link } from '@inertiajs/react';
import { ArrowLeft, ArrowRight } from 'lucide-react';
import { ArticleMedia } from '@/components/site/article-media';
import type { TeamMember } from '@/types/about';

interface TeamSliderProps {
    eyebrow: string;
    heading: string;
    sublabel: string;
    members: TeamMember[];
}

/**
 * A horizontal, scroll-snap slider rather than a JS-state carousel: it
 * scales to any number of members with no "how many per view" logic to
 * maintain, works with touch/trackpad scrolling for free, and degrades
 * gracefully (still fully usable) if the arrow buttons' JS never runs.
 */
export function TeamSlider({ eyebrow, heading, sublabel, members }: TeamSliderProps) {
    const trackRef = useRef<HTMLDivElement>(null);

    function scrollByCard(direction: 1 | -1) {
        const track = trackRef.current;
        if (!track) return;

        const card = track.querySelector<HTMLElement>('[data-slider-card]');
        const amount = (card?.offsetWidth ?? 280) + 24; // card width + gap-6
        track.scrollBy({ left: amount * direction, behavior: 'smooth' });
    }

    return (
        <section className="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div className="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <span className="text-[11px] font-semibold tracking-widest text-red-600 uppercase">
                        {eyebrow}
                    </span>
                    <h2 className="mt-1 font-serif text-3xl font-bold">{heading}</h2>
                </div>

                <div className="flex items-center gap-3">
                    <span className="text-[11px] tracking-wide text-muted-foreground uppercase">{sublabel}</span>
                    {members.length > 1 && (
                        <div className="flex gap-1.5">
                            <button
                                type="button"
                                onClick={() => scrollByCard(-1)}
                                aria-label="Previous team members"
                                className="rounded-full border border-border p-1.5 text-muted-foreground transition-colors hover:border-red-600 hover:text-red-600"
                            >
                                <ArrowLeft className="size-4" />
                            </button>
                            <button
                                type="button"
                                onClick={() => scrollByCard(1)}
                                aria-label="Next team members"
                                className="rounded-full border border-border p-1.5 text-muted-foreground transition-colors hover:border-red-600 hover:text-red-600"
                            >
                                <ArrowRight className="size-4" />
                            </button>
                        </div>
                    )}
                </div>
            </div>

            <div
                ref={trackRef}
                className="no-scrollbar mt-8 flex snap-x snap-mandatory gap-6 overflow-x-auto scroll-smooth pb-2"
            >
                {members.map((member) => {
                    const content = (
                        <>
                            <div className="relative">
                                <ArticleMedia src={member.photo} alt={member.name} className="aspect-[4/5] w-full" />
                                <span className="absolute bottom-3 left-3 rounded-sm bg-black/80 px-2 py-1 text-[10px] font-semibold tracking-widest text-white uppercase">
                                    {member.role}
                                </span>
                            </div>
                            <h3 className="mt-3 font-serif text-lg font-bold">{member.name}</h3>
                            <p className="text-xs font-semibold tracking-wide text-red-600 uppercase">{member.role}</p>
                            {member.bio && (
                                <p className="mt-1 line-clamp-3 text-sm text-muted-foreground">{member.bio}</p>
                            )}
                        </>
                    );

                    return member.href ? (
                        <Link
                            key={member.id}
                            href={member.href}
                            data-slider-card
                            className="group flex w-64 shrink-0 snap-start flex-col sm:w-72"
                        >
                            {content}
                        </Link>
                    ) : (
                        <div key={member.id} data-slider-card className="flex w-64 shrink-0 snap-start flex-col sm:w-72">
                            {content}
                        </div>
                    );
                })}
            </div>
        </section>
    );
}