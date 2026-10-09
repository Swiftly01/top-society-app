import { ChevronLeft, ChevronRight } from 'lucide-react';
import { useCallback, useEffect, useRef, useState } from 'react';
import type { ReactNode, TouchEvent } from 'react';
import type { PDFDocumentProxy } from 'pdfjs-dist';
import { PdfPage } from '@/components/home/pdf-page';
import { hasNextPage, hasPrevPage, nextPage, prevPage, visiblePages } from '@/lib/pdf-spread';
import { cn } from '@/lib/utils';

/** Width ÷ height at which the reader switches from one page to a two-page spread. */
const SPREAD_MIN_RATIO = 1.3;
/** Minimum horizontal travel, in px, for a touch gesture to count as a swipe. */
const SWIPE_THRESHOLD = 40;

interface PdfReaderProps {
    pdf: PDFDocumentProxy;
    /** Current page (1-based). Controlled, so the inline card and the full-screen view stay in step. */
    page: number;
    onPageChange: (page: number) => void;
    /** Left/right arrow keys turn pages — enable only where the reader owns the screen. */
    keyboard?: boolean;
    /** Show "20–21 / 39" at the bottom. */
    showCounter?: boolean;
    /** Optional cover image shown in place of page 1. Without it, the PDF's own first page is used. */
    coverSrc?: string | null;
    /** 'dark' = the reader sits on a dark background, so controls are white. */
    variant?: 'light' | 'dark';
    /** Sizing + padding of the reading area (padding leaves room for the arrows). */
    className?: string;
    /** Extra overlay controls, e.g. the full-screen button. */
    children?: ReactNode;
}

/**
 * Shows a PDF as a book: a two-page spread when the area is wide enough
 * (the cover alone first), one page when it is narrow. Side arrows, swipe
 * and (optionally) the keyboard turn the pages. Only the pages on screen
 * are ever rendered.
 */
export function PdfReader({
    pdf,
    page,
    onPageChange,
    keyboard = false,
    showCounter = false,
    coverSrc,
    variant = 'light',
    className,
    children,
}: PdfReaderProps) {
    const numPages = pdf.numPages;
    const areaRef = useRef<HTMLDivElement>(null);
    const touchStartX = useRef<number | null>(null);
    const [size, setSize] = useState({ width: 0, height: 0 });

    useEffect(() => {
        const area = areaRef.current;

        if (!area) return;

        const observer = new ResizeObserver(([entry]) => {
            if (!entry) return;

            setSize({
                width: Math.floor(entry.contentRect.width),
                height: Math.floor(entry.contentRect.height),
            });
        });

        observer.observe(area);

        return () => observer.disconnect();
    }, []);

    const spread = size.height > 0 && size.width / size.height >= SPREAD_MIN_RATIO;
    const visible = visiblePages(page, numPages, spread);
    const canGoPrev = hasPrevPage(page, numPages, spread);
    const canGoNext = hasNextPage(page, numPages, spread);

    const goPrev = useCallback(() => onPageChange(prevPage(page, numPages, spread)), [onPageChange, page, numPages, spread]);
    const goNext = useCallback(() => onPageChange(nextPage(page, numPages, spread)), [onPageChange, page, numPages, spread]);

    useEffect(() => {
        if (!keyboard) return;

        const onKeyDown = (event: KeyboardEvent) => {
            if (event.key === 'ArrowLeft' && canGoPrev) goPrev();
            if (event.key === 'ArrowRight' && canGoNext) goNext();
        };

        window.addEventListener('keydown', onKeyDown);

        return () => window.removeEventListener('keydown', onKeyDown);
    }, [keyboard, canGoPrev, canGoNext, goPrev, goNext]);

    const handleTouchStart = (event: TouchEvent) => {
        touchStartX.current = event.touches[0]?.clientX ?? null;
    };

    const handleTouchEnd = (event: TouchEvent) => {
        if (touchStartX.current === null) return;

        const delta = (event.changedTouches[0]?.clientX ?? touchStartX.current) - touchStartX.current;
        touchStartX.current = null;

        if (Math.abs(delta) < SWIPE_THRESHOLD) return;

        if (delta < 0 && canGoNext) goNext();
        if (delta > 0 && canGoPrev) goPrev();
    };

    // Every page gets the same box, so the lone cover is the same size as
    // each half of a spread.
    const boxWidth = spread ? Math.floor(size.width / 2) : size.width;
    const isPair = spread && visible.length === 2;
    const controlClass = cn(
        'absolute top-1/2 z-10 -translate-y-1/2 rounded-full p-1.5',
        variant === 'dark' ? 'text-white hover:bg-white/15' : 'text-foreground hover:bg-foreground/10',
    );

    return (
        <div
            ref={areaRef}
            className={cn('relative flex items-center justify-center', className)}
            onTouchStart={handleTouchStart}
            onTouchEnd={handleTouchEnd}
        >
            {size.width > 0 &&
                visible.map((pageNumber, i) => (
                    <div
                        key={pageNumber}
                        className={cn(
                            'flex h-full items-center',
                            // Pages meet at the spine, like an open book.
                            isPair ? (i === 0 ? 'justify-end' : 'justify-start') : 'justify-center',
                        )}
                        style={{ width: boxWidth }}
                    >
                        {pageNumber === 1 && coverSrc ? (
                            <img src={coverSrc} alt="" draggable={false} className="size-full object-contain drop-shadow-md" />
                        ) : (
                            <PdfPage pdf={pdf} pageNumber={pageNumber} width={boxWidth} height={size.height} />
                        )}
                    </div>
                ))}

            {canGoPrev && (
                <button
                    type="button"
                    onClick={goPrev}
                    aria-label="Previous page"
                    className={cn(controlClass, 'left-0')}
                >
                    <ChevronLeft className="size-6" strokeWidth={3} />
                </button>
            )}
            {canGoNext && (
                <button
                    type="button"
                    onClick={goNext}
                    aria-label="Next page"
                    className={cn(controlClass, 'right-0')}
                >
                    <ChevronRight className="size-6" strokeWidth={3} />
                </button>
            )}

            {showCounter && (
                <span className="pointer-events-none absolute bottom-1 left-1/2 z-10 -translate-x-1/2 rounded-full bg-black/40 px-3 py-1 text-xs font-medium text-white tabular-nums">
                    {visible.length === 2 ? `${visible[0]}–${visible[1]}` : visible[0]} / {numPages}
                </span>
            )}

            {children}
        </div>
    );
}
