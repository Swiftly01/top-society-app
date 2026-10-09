import { ChevronLeft, ChevronRight } from 'lucide-react';
import { useEffect, useRef, useState } from 'react';
import { useAutoSlide } from '@/hooks/use-auto-slide';
import { cn } from '@/lib/utils';
import type { Advert } from '@/types/content';

interface AdvertSliderProps {
    adverts: Advert[];
    /** Screen-reader name for this slot, e.g. "Sponsored banners". */
    ariaLabel?: string;
    /** Tailwind aspect-ratio classes for the slot's shape. */
    aspectClassName?: string;
    /** Milliseconds between auto-advances. Set to 0 to disable autoplay. */
    intervalMs?: number;
}

/**
 * Sliding banner for the adverts the admin manages. Every slot on the site
 * uses this one component — a slot only differs in its shape
 * (`aspectClassName`) and the list of adverts it is given. A slide is an
 * image or a muted looping video; with a link it opens the advertiser's
 * site in a new tab via AdvertController::click (which counts the click
 * first), without one it is a plain banner. Renders nothing when there
 * are no live adverts.
 */
export function AdvertSlider({
    adverts,
    ariaLabel = 'Advertisements',
    aspectClassName = 'aspect-video',
    intervalMs = 5000,
}: AdvertSliderProps) {
    const { index, goTo, next, prev, containerProps } = useAutoSlide({ count: adverts.length, intervalMs });

    if (adverts.length === 0) return null;

    return (
        <section
            className="group/adverts relative"
            role="region"
            aria-roledescription="carousel"
            aria-label={ariaLabel}
            {...containerProps}
        >
            <div className={cn('relative overflow-hidden rounded-sm bg-muted', aspectClassName)}>
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
 * in a new tab; without one it is a plain element, so nothing suggests
 * it can be clicked.
 */
function AdvertSlide({ advert, isActive, isFirst }: AdvertSlideProps) {
    const media = advert.src ? (
        advert.type === 'video' ? (
            <AdvertVideo src={advert.src} title={advert.title} isActive={isActive} />
        ) : (
            <img
                src={advert.src}
                alt={advert.title}
                // Only the first slide is visible on load; the rest can wait.
                loading={isFirst ? 'eager' : 'lazy'}
                draggable={false}
                className="size-full object-cover"
            />
        )
    ) : null;

    if (!advert.href) {
        return (
            <div className="block size-full shrink-0" aria-hidden={!isActive}>
                {media}
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
            {media}
        </a>
    );
}

interface AdvertVideoProps {
    src: string;
    title: string;
    isActive: boolean;
}

/**
 * A muted, looping, inline video. Built so that a slot full of videos
 * stays cheap:
 *  - a video's file isn't requested until its slide has been shown once;
 *  - it only plays while its slide is the active one AND on screen, so
 *    nothing decodes in the background or off-screen;
 *  - visitors who prefer reduced motion get a still first frame.
 */
function AdvertVideo({ src, title, isActive }: AdvertVideoProps) {
    const videoRef = useRef<HTMLVideoElement>(null);
    const [onScreen, setOnScreen] = useState(false);
    // Derived-state pattern: flips to true during the render in which the slide first becomes active.
    const [armed, setArmed] = useState(isActive);

    if (isActive && !armed) setArmed(true);

    useEffect(() => {
        const video = videoRef.current;

        if (!video) return;

        const observer = new IntersectionObserver(([entry]) => setOnScreen(Boolean(entry?.isIntersecting)), {
            threshold: 0.25,
        });

        observer.observe(video);

        return () => observer.disconnect();
    }, []);

    useEffect(() => {
        const video = videoRef.current;

        if (!video) return;

        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        if (isActive && onScreen && !reduceMotion) {
            void video.play().catch(() => undefined);
        } else {
            video.pause();
        }
    }, [isActive, onScreen, armed]);

    return (
        <video
            ref={videoRef}
            src={armed ? src : undefined}
            preload={armed ? 'auto' : 'none'}
            aria-label={title}
            muted
            loop
            playsInline
            disablePictureInPicture
            className="size-full object-cover"
        />
    );
}
