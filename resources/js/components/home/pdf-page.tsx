import { useEffect, useRef } from 'react';
import type { PDFDocumentProxy, RenderTask } from 'pdfjs-dist';

interface PdfPageProps {
    pdf: PDFDocumentProxy;
    pageNumber: number;
    /** Largest box, in CSS px, the page may fill. The page is scaled to fit it. */
    width: number;
    height: number;
}

/**
 * Draws one PDF page onto a canvas, scaled to fit `width` × `height`
 * and rendered at the screen's pixel density (capped at 2×) so text is
 * sharp without allocating huge canvases on high-DPI phones.
 */
export function PdfPage({ pdf, pageNumber, width, height }: PdfPageProps) {
    const canvasRef = useRef<HTMLCanvasElement>(null);
    const taskRef = useRef<RenderTask | null>(null);

    useEffect(() => {
        const canvas = canvasRef.current;

        if (!canvas || width <= 0 || height <= 0) return;

        let cancelled = false;

        (async () => {
            // PDF.js refuses two renders on one canvas at once, so let the
            // previous (now cancelled) render finish unwinding first.
            const previous = taskRef.current;
            previous?.cancel();
            await previous?.promise.catch(() => undefined);

            const page = await pdf.getPage(pageNumber);

            if (cancelled) return;

            const natural = page.getViewport({ scale: 1 });
            const fit = Math.min(width / natural.width, height / natural.height);
            const density = Math.min(window.devicePixelRatio || 1, 2);
            const viewport = page.getViewport({ scale: fit * density });
            const context = canvas.getContext('2d');

            if (!context) return;

            canvas.width = Math.floor(viewport.width);
            canvas.height = Math.floor(viewport.height);
            canvas.style.width = `${Math.floor(natural.width * fit)}px`;
            canvas.style.height = `${Math.floor(natural.height * fit)}px`;

            const task = page.render({ canvas, canvasContext: context, viewport });
            taskRef.current = task;

            await task.promise;
        })().catch(() => {
            // A render cancelled by a page flip or resize rejects — expected.
        });

        return () => {
            cancelled = true;
            taskRef.current?.cancel();
        };
    }, [pdf, pageNumber, width, height]);

    return <canvas ref={canvasRef} className="block bg-white shadow-md" />;
}
