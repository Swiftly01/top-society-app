import { useEffect, useRef, useState } from 'react';

/**
 * True once the referenced element has come within `rootMargin` of the
 * viewport — and it stays true afterwards. Used to hold off heavy work
 * (here: fetching a PDF) until a visitor is about to see the section,
 * so people who never scroll that far never pay for it.
 */
export function useInView<T extends Element>(rootMargin = '200px') {
    const ref = useRef<T>(null);
    const [inView, setInView] = useState(() => typeof window !== 'undefined' && !('IntersectionObserver' in window));

    useEffect(() => {
        const element = ref.current;

        if (!element || inView) return;

        const observer = new IntersectionObserver(
            ([entry]) => {
                if (entry?.isIntersecting) {
                    setInView(true);
                    observer.disconnect();
                }
            },
            { rootMargin },
        );

        observer.observe(element);

        return () => observer.disconnect();
    }, [inView, rootMargin]);

    return { ref, inView };
}
