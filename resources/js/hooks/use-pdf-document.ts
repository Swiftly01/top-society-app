import { useEffect, useState } from 'react';
import type { PDFDocumentProxy } from 'pdfjs-dist';

type PdfJs = typeof import('pdfjs-dist');

export type PdfStatus = 'idle' | 'loading' | 'ready' | 'error';

let pdfjsPromise: Promise<PdfJs> | null = null;

/**
 * PDF.js is large, so it is imported on demand — it becomes its own
 * chunk and is only downloaded when a visitor actually reaches a
 * magazine. The promise is cached so every viewer shares one copy and
 * one worker; a failed import clears the cache so a retry is possible.
 */
function loadPdfJs(): Promise<PdfJs> {
    pdfjsPromise ??= Promise.all([import('pdfjs-dist'), import('pdfjs-dist/build/pdf.worker.min.mjs?url')])
        .then(([pdfjs, worker]) => {
            pdfjs.GlobalWorkerOptions.workerSrc = worker.default;

            return pdfjs;
        })
        .catch((error: unknown) => {
            pdfjsPromise = null;

            throw error;
        });

    return pdfjsPromise;
}

interface LoadResult {
    url: string;
    pdf: PDFDocumentProxy | null;
}

/**
 * Opens the PDF at `url` once `enabled` is true. `disableAutoFetch`
 * makes PDF.js request only the byte ranges of the pages being shown
 * rather than pulling the whole file in the background, so a 40-page
 * issue costs a few hundred KB to open, not its full size.
 */
export function usePdfDocument(url: string | null | undefined, enabled: boolean) {
    const [result, setResult] = useState<LoadResult | null>(null);

    useEffect(() => {
        if (!url || !enabled) return;

        let cancelled = false;
        let destroy: (() => void) | null = null;

        loadPdfJs()
            .then((pdfjs) => {
                if (cancelled) return null;

                const task = pdfjs.getDocument({ url, disableAutoFetch: true });
                destroy = () => void task.destroy();

                return task.promise;
            })
            .then((pdf) => {
                if (!cancelled && pdf) setResult({ url, pdf });
            })
            .catch(() => {
                if (!cancelled) setResult({ url, pdf: null });
            });

        return () => {
            cancelled = true;
            destroy?.();
        };
    }, [url, enabled]);

    // Derived rather than stored: a result for a previous url never leaks
    // into the next issue the visitor switches to.
    const current = result && result.url === url ? result : null;

    const status: PdfStatus = !url || !enabled ? 'idle' : !current ? 'loading' : current.pdf ? 'ready' : 'error';

    return { status, pdf: current?.pdf ?? null };
}
