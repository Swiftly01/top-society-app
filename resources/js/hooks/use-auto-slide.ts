import { useCallback, useEffect, useRef, useState } from 'react';
import type { TouchEvent } from 'react';

interface UseAutoSlideOptions {
    /** Number of slides. */
    count: number;
    /** Milliseconds between auto-advances. 0 disables autoplay. */
    intervalMs?: number;
}

/** Minimum horizontal travel, in px, for a touch gesture to count as a swipe. */
const SWIPE_THRESHOLD = 40;

/**
 * Shared behaviour for the homepage sliders (adverts, magazines): the
 * active index with wrap-around, autoplay that pauses on hover/focus and
 * is skipped for prefers-reduced-motion or a single slide, and touch
 * swipe. Spread `containerProps` onto the slider's root element.
 *
 * Autoplay is a timeout keyed on the active index rather than a
 * free-running interval, so a manual click/swipe restarts the countdown
 * instead of the slide jumping again a moment later.
 */
export function useAutoSlide({ count, intervalMs = 5000 }: UseAutoSlideOptions) {
    const [active, setActive] = useState(0);
    const [paused, setPaused] = useState(false);
    const touchStartX = useRef<number | null>(null);

    // The slide list can shrink between Inertia visits; never point past the end.
    const index = count > 0 ? active % count : 0;

    const goTo = useCallback(
        (target: number) => {
            if (count > 0) setActive(((target % count) + count) % count);
        },
        [count],
    );

    const next = useCallback(() => goTo(index + 1), [goTo, index]);
    const prev = useCallback(() => goTo(index - 1), [goTo, index]);

    useEffect(() => {
        if (intervalMs <= 0 || count <= 1 || paused) return;
        if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

        const timer = setTimeout(() => goTo(index + 1), intervalMs);

        return () => clearTimeout(timer);
    }, [count, goTo, index, intervalMs, paused]);

    const containerProps = {
        onMouseEnter: () => setPaused(true),
        onMouseLeave: () => setPaused(false),
        onFocus: () => setPaused(true),
        onBlur: () => setPaused(false),
        onTouchStart: (event: TouchEvent) => {
            touchStartX.current = event.touches[0]?.clientX ?? null;
        },
        onTouchEnd: (event: TouchEvent) => {
            if (touchStartX.current === null) return;

            const delta = (event.changedTouches[0]?.clientX ?? touchStartX.current) - touchStartX.current;
            touchStartX.current = null;

            if (Math.abs(delta) >= SWIPE_THRESHOLD) {
                goTo(delta < 0 ? index + 1 : index - 1);
            }
        },
    };

    return { index, goTo, next, prev, containerProps };
}
