import { ChevronLeft, ChevronRight } from 'lucide-react';
import { useAutoSlide } from '@/hooks/use-auto-slide';
import { cn } from '@/lib/utils';
import type { Advert } from '@/types/content';

interface AdvertSliderProps {
    adverts: Advert[];
    /** Milliseconds between auto-advances. Set to 0 to disable autoplay. */
    intervalMs?: number;
}

/**
 * Sliding banner for the adverts the admin manages. Each slide is one
 * image that links to the advertiser's website in a new tab — via
 * AdvertController::click, which counts the click first. Renders nothing
 * when there are no live adverts.
 */
export function AdvertSlider({ adverts, intervalMs = 5000 }: AdvertSliderProps) {
    const { index, goTo, next, prev, containerProps } = useAutoSlide({ count: adverts.length, intervalMs });

    if (adverts.length === 0) return null;

    return (
        <section
            className="group/adverts relative"
            role="region"
            aria-roledescription="carousel"
            aria-label="Advertisements"
            {...containerProps}
        >
            <div className="relative aspect-video overflow-hidden rounded-sm bg-muted">
                <div
                    className="flex size-full transition-transform duration-500 ease-in-out motion-reduce:transition-none"
                    style={{ transform: `translateX(-${index * 100}%)` }}
                >
                    {adverts.map((advert, i) => (
                        <AdvertSlide key={advert.id} advert={advert} isActive={i === index} isFirst={i === 0} />
                    ))}
                </div>

                <span className="pointer-events-none absolute top-2 left-2 rounded-sm bg-black/60 px-1.5 py-0.5 text-[10px] font-semibold tracking-wide text-white uppercase">
                    Advertisement
                </span>

                {adverts.length > 1 && (
                    <>
                        <button
                            type="button"
                            onClick={prev}
                            aria-label="Previous advertisement"
                            className="absolute top-1/2 left-2 z-10 hidden -translate-y-1/2 rounded-full bg-black/40 p-1.5 text-white opacity-0 transition-opacity group-hover/adverts:opacity-100 hover:bg-black/60 focus-visible:opacity-100 sm:block"
                        >
                            <ChevronLeft className="size-4" />
                        </button>
                        <button
                            type="button"
                            onClick={next}
                            aria-label="Next advertisement"
                            className="absolute top-1/2 right-2 z-10 hidden -translate-y-1/2 rounded-full bg-black/40 p-1.5 text-white opacity-0 transition-opacity group-hover/adverts:opacity-100 hover:bg-black/60 focus-visible:opacity-100 sm:block"
                        >
                            <ChevronRight className="size-4" />
                        </button>

                        <div className="absolute right-0 bottom-2 left-0 z-10 flex justify-center gap-1.5">
                            {adverts.map((advert, i) => (
                                <button
                                    key={advert.id}
                                    type="button"
                                    onClick={() => goTo(i)}
                                    aria-label={`Show advertisement ${i + 1} of ${adverts.length}`}
                                    aria-current={i === index}
                                    className={cn(
                                        'h-1.5 rounded-full transition-all',
                                        i === index ? 'w-5 bg-white' : 'w-1.5 bg-white/50 hover:bg-white/80',
                                    )}
                                />
                            ))}
                        </div>
                    </>
                )}
            </div>
        </section>
    );
}

interface AdvertSlideProps {
    advert: Advert;
    isActive: boolean;
    isFirst: boolean;
}

/**
 * One banner. With a link it is an anchor to the click tracker, opening
 * in a new tab; without one it is a plain image, so nothing suggests it
 * can be clicked.
 */
function AdvertSlide({ advert, isActive, isFirst }: AdvertSlideProps) {
    const image = advert.image ? (
        <img
            src={advert.image}
            alt={advert.title}
            // Only the first slide is visible on load; the rest can wait.
            loading={isFirst ? 'eager' : 'lazy'}
            draggable={false}
            className="size-full object-cover"
        />
    ) : null;

    if (!advert.href) {
        return (
            <div className="block size-full shrink-0" aria-hidden={!isActive}>
                {image}
            </div>
        );
    }

    return (
        <a
            href={advert.href}
            target="_blank"
            rel="sponsored noopener noreferrer"
            aria-hidden={!isActive}
            tabIndex={isActive ? 0 : -1}
            aria-label={`${advert.title} (advertisement, opens in a new tab)`}
            className="block size-full shrink-0"
        >
            {image}
        </a>
    );
}
