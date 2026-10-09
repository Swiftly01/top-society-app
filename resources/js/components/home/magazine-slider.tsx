import { Download, Maximize2, X } from 'lucide-react';
import { useCallback, useEffect, useRef, useState } from 'react';
import { createPortal } from 'react-dom';
import { PdfReader } from '@/components/home/pdf-reader';
import { ArticleMedia } from '@/components/site/article-media';
import { useInView } from '@/hooks/use-in-view';
import { usePdfDocument } from '@/hooks/use-pdf-document';
import { cn } from '@/lib/utils';
import type { Magazine } from '@/types/content';
import type { PDFDocumentProxy } from 'pdfjs-dist';

interface MagazineSliderProps {
    /** Newest first. */
    magazines: Magazine[];
}

/**
 * Reads the published magazine issues in place. Each issue's PDF is shown
 * page by page, starting from its first page (or from the admin's
 * optional cover image, when one was uploaded). Arrows turn pages and
 * the centre icon opens a full-screen reader with a download button.
 * The dots switch between issues. Only the selected issue's PDF is ever
 * fetched, and only once the section is near the viewport. Renders
 * nothing without issues.
 */
export function MagazineSlider({ magazines }: MagazineSliderProps) {
    const [active, setActive] = useState(0);

    if (magazines.length === 0) return null;

    const index = active % magazines.length;
    const magazine = magazines[index];

    return (
        <section role="region" aria-roledescription="carousel" aria-label="Magazine issues">
            {/* Keyed so switching issue starts a fresh viewer on page 1. */}
            <MagazineViewer key={magazine.id} magazine={magazine} />

            {magazines.length > 1 && (
                <div className="mt-2 flex justify-center gap-1.5">
                    {magazines.map((item, i) => (
                        <button
                            key={item.id}
                            type="button"
                            onClick={() => setActive(i)}
                            aria-label={`Show issue ${i + 1} of ${magazines.length}: ${item.title}`}
                            aria-current={i === index}
                            className={cn(
                                'h-1.5 rounded-full transition-all',
                                i === index ? 'w-5 bg-red-600' : 'w-1.5 bg-border hover:bg-muted-foreground',
                            )}
                        />
                    ))}
                </div>
            )}
        </section>
    );
}

function MagazineViewer({ magazine }: { magazine: Magazine }) {
    const { ref, inView } = useInView<HTMLDivElement>();
    const { status, pdf } = usePdfDocument(magazine.pdfUrl, inView);
    const [page, setPage] = useState(1);
    const [fullscreen, setFullscreen] = useState(false);
    const closeFullscreen = useCallback(() => setFullscreen(false), []);

    return (
        <>
            <div ref={ref} className="group/viewer relative h-64 overflow-hidden rounded-sm border border-border bg-muted">
                {status === 'ready' && pdf ? (
                    <PdfReader
                        pdf={pdf}
                        page={page}
                        onPageChange={setPage}
                        coverSrc={magazine.coverImage}
                        className="size-full px-9 py-3"
                    >
                        <button
                            type="button"
                            onClick={() => setFullscreen(true)}
                            aria-label={`Open ${magazine.title} full screen`}
                            className="absolute top-1/2 left-1/2 z-10 -translate-x-1/2 -translate-y-1/2 rounded-xl bg-black/50 p-3 text-white opacity-0 backdrop-blur-sm transition-opacity group-hover/viewer:opacity-100 focus-visible:opacity-100 [@media(hover:none)]:opacity-80"
                        >
                            <Maximize2 className="size-6" />
                        </button>
                    </PdfReader>
                ) : (
                    // Not loaded yet, no PDF (external link only), or the PDF
                    // failed to open: the cover stands in, and still links out.
                    <MagazineCover magazine={magazine} />
                )}
            </div>

            {fullscreen && pdf && (
                <FullscreenReader
                    pdf={pdf}
                    page={page}
                    onPageChange={setPage}
                    title={magazine.title}
                    coverSrc={magazine.coverImage}
                    downloadHref={magazine.downloadHref}
                    onClose={closeFullscreen}
                />
            )}
        </>
    );
}

function MagazineCover({ magazine }: { magazine: Magazine }) {
    const { title, coverImage, downloadHref, isExternal } = magazine;
    const className = 'flex size-full items-center justify-center p-3';
    const image = <ArticleMedia src={coverImage} alt={title} className="aspect-[3/4] h-full" />;

    if (!downloadHref) return <div className={className}>{image}</div>;

    return (
        <a
            href={downloadHref}
            className={className}
            aria-label={isExternal ? `Read ${title}` : `Download ${title} as PDF`}
            {...(isExternal ? { target: '_blank', rel: 'noopener noreferrer' } : {})}
        >
            {image}
        </a>
    );
}

interface FullscreenReaderProps {
    pdf: PDFDocumentProxy;
    page: number;
    onPageChange: (page: number) => void;
    title: string;
    coverSrc?: string | null;
    downloadHref?: string | null;
    onClose: () => void;
}

/**
 * Full-screen reader. It asks the browser for real fullscreen (Esc exits,
 * like the Issuu embed) but is also a fixed overlay, so it still works on
 * browsers that refuse element fullscreen (iPhone Safari) — the X button
 * and Esc close it either way. The already-loaded PDF is reused, so
 * opening it costs no extra download.
 */
function FullscreenReader({ pdf, page, onPageChange, title, coverSrc, downloadHref, onClose }: FullscreenReaderProps) {
    const overlayRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        const previousOverflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden';

        try {
            // Safari < 16.4 returns undefined here rather than a promise.
            void Promise.resolve(overlayRef.current?.requestFullscreen()).catch(() => undefined);
        } catch {
            // Fullscreen refused — the overlay alone still fills the viewport.
        }

        const onFullscreenChange = () => {
            if (!document.fullscreenElement) onClose();
        };
        const onKeyDown = (event: KeyboardEvent) => {
            if (event.key === 'Escape') onClose();
        };

        document.addEventListener('fullscreenchange', onFullscreenChange);
        window.addEventListener('keydown', onKeyDown);

        return () => {
            document.removeEventListener('fullscreenchange', onFullscreenChange);
            window.removeEventListener('keydown', onKeyDown);
            document.body.style.overflow = previousOverflow;

            if (document.fullscreenElement) void document.exitFullscreen().catch(() => undefined);
        };
    }, [onClose]);

    return createPortal(
        <div
            ref={overlayRef}
            role="dialog"
            aria-modal="true"
            aria-label={title}
            className="fixed inset-0 z-[100] flex flex-col bg-neutral-900 text-white"
        >
            <div className="flex shrink-0 justify-end gap-1 p-3">
                {downloadHref && (
                    <a
                        href={downloadHref}
                        aria-label={`Download ${title} as PDF`}
                        className="rounded-full p-2 hover:bg-white/15"
                    >
                        <Download className="size-5" />
                    </a>
                )}
                <button
                    type="button"
                    onClick={onClose}
                    aria-label="Close full screen"
                    className="rounded-full p-2 hover:bg-white/15"
                >
                    <X className="size-5" />
                </button>
            </div>

            <PdfReader
                pdf={pdf}
                page={page}
                onPageChange={onPageChange}
                keyboard
                showCounter
                variant="dark"
                coverSrc={coverSrc}
                className="min-h-0 flex-1 px-12 pb-10"
            />
        </div>,
        document.body,
    );
}
