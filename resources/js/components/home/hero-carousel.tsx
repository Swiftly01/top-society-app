import { useCallback, useEffect, useRef, useState } from 'react';
import { Link } from '@inertiajs/react';
import { ChevronLeft, ChevronRight } from 'lucide-react';
import { ArticleMedia } from '@/components/site/article-media';
import { cn } from '@/lib/utils';
import type { FeaturedArticle } from '@/types/content';

interface HeroCarouselProps {
    articles: FeaturedArticle[];
    /** Milliseconds between auto-advances. Set to 0 to disable autoplay. */
    intervalMs?: number;
}

export function HeroCarousel({ articles, intervalMs = 6000 }: HeroCarouselProps) {
    const [active, setActive] = useState(0);
    const [paused, setPaused] = useState(false);
    const timerRef = useRef<ReturnType<typeof setInterval> | null>(null);

    const goTo = useCallback(
        (index: number) => {
            setActive(((index % articles.length) + articles.length) % articles.length);
        },
        [articles.length],
    );

    const next = useCallback(() => goTo(active + 1), [active, goTo]);
    const prev = useCallback(() => goTo(active - 1), [active, goTo]);

    // Autoplay — paused on hover/focus and skipped entirely for
    // prefers-reduced-motion or when there's only one slide.
    useEffect(() => {
        if (intervalMs <= 0 || articles.length <= 1 || paused) return;
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        timerRef.current = setInterval(() => {
            setActive((current) => (current + 1) % articles.length);
        }, intervalMs);

        return () => {
            if (timerRef.current) clearInterval(timerRef.current);
        };
    }, [articles.length, intervalMs, paused]);

    if (articles.length === 0) return null;

    return (
        <div
            // Was a flat min-h-125 (500px) at every screen size, including
            // phones — on a narrow viewport that's most of the screen taken
            // up by the hero before any real content shows. Scaling the
            // minimum height up by breakpoint keeps it proportionate on
            // mobile while still landing on the original 500px at lg+ (the
            // height MagazineCard's lg:max-h-125 is matched against).
            // Slides below are `absolute inset-0`, so their content never
            // contributes to this container's own height — the text inside
            // has to stay within min-h-85 (340px) on its own (see the
            // line-clamp and hidden-on-mobile excerpt below), or it'd get
            // silently clipped by overflow-hidden instead of visibly
            // breaking anything.
            className="group/carousel relative min-h-85 overflow-hidden rounded-sm sm:min-h-100 lg:min-h-125"
            onMouseEnter={() => setPaused(true)}
            onMouseLeave={() => setPaused(false)}
            onFocus={() => setPaused(true)}
            onBlur={() => setPaused(false)}
            role="region"
            aria-roledescription="carousel"
            aria-label="Featured stories"
        >
            {articles.map((article, index) => (
                <Link
                    key={article.id}
                    href={article.href}
                    aria-hidden={index !== active}
                    tabIndex={index === active ? 0 : -1}
                    className={cn(
                        'absolute inset-0 flex flex-col justify-end transition-opacity duration-700 ease-in-out',
                        index === active ? 'pointer-events-auto opacity-100' : 'pointer-events-none opacity-0',
                    )}
                >
                    <ArticleMedia src={article.image} alt={article.title} className="absolute inset-0 size-full" />
                    <div className="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent" />

                    <div className="relative flex flex-col gap-2 p-4 sm:gap-3 sm:p-6 lg:p-8">
                        <span className="w-fit rounded-sm bg-red-600 px-2 py-1 text-[11px] font-semibold tracking-wide text-white uppercase">
                            {article.badge}
                        </span>
                        <h2 className="line-clamp-2 font-serif text-xl leading-tight font-bold text-white sm:text-2xl lg:text-4xl">
                            {article.title}
                        </h2>
                        {/* Excerpts have no fixed length, so they're the
                            highest-risk element for overflowing a short
                            mobile slide — hidden below sm, where there's
                            the least room to spare. */}
                        {article.excerpt && (
                            <p className="line-clamp-2 hidden max-w-xl text-sm text-neutral-200 sm:block">
                                {article.excerpt}
                            </p>
                        )}
                        <p className="text-xs text-neutral-300">
                            {article.author && <>By {article.author} · </>}
                            {article.readTime}
                        </p>
                    </div>
                </Link>
            ))}

            {articles.length > 1 && (
                <>
                    <button
                        type="button"
                        onClick={prev}
                        aria-label="Previous story"
                        className="absolute top-1/2 left-3 z-10 -translate-y-1/2 rounded-full bg-black/40 p-2 text-white opacity-0 transition-opacity group-hover/carousel:opacity-100 hover:bg-black/60 focus-visible:opacity-100"
                    >
                        <ChevronLeft className="size-5" />
                    </button>
                    <button
                        type="button"
                        onClick={next}
                        aria-label="Next story"
                        className="absolute top-1/2 right-3 z-10 -translate-y-1/2 rounded-full bg-black/40 p-2 text-white opacity-0 transition-opacity group-hover/carousel:opacity-100 hover:bg-black/60 focus-visible:opacity-100"
                    >
                        <ChevronRight className="size-5" />
                    </button>

                    <div className="absolute right-0 bottom-4 left-0 z-10 flex justify-center gap-1.5">
                        {articles.map((article, index) => (
                            <button
                                key={article.id}
                                type="button"
                                onClick={() => goTo(index)}
                                aria-label={`Go to story ${index + 1}: ${article.title}`}
                                aria-current={index === active}
                                className={cn(
                                    'h-1.5 rounded-full transition-all',
                                    index === active ? 'w-6 bg-white' : 'w-1.5 bg-white/50 hover:bg-white/80',
                                )}
                            />
                        ))}
                    </div>
                </>
            )}
        </div>
    );
}
