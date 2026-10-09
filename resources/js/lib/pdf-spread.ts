/**
 * Page maths for the magazine reader. Magazines are read as a book: when
 * there is room the reader shows two-page spreads — the cover on its own,
 * then 2–3, 4–5, 6–7 … — and on a narrow screen it shows one page at a
 * time. These helpers are pure so the same rules drive the inline card
 * and the full-screen view.
 */

export function clampPage(page: number, numPages: number): number {
    return Math.min(Math.max(Math.round(page) || 1, 1), Math.max(numPages, 1));
}

/** The page numbers on screen when `page` is the current page. */
export function visiblePages(page: number, numPages: number, spread: boolean): number[] {
    const current = clampPage(page, numPages);

    if (!spread || current === 1) return [current];

    // In a spread the left-hand page is always even (2, 4, 6 …).
    const left = current % 2 === 0 ? current : current - 1;

    return left + 1 <= numPages ? [left, left + 1] : [left];
}

export function hasPrevPage(page: number, numPages: number, spread: boolean): boolean {
    return visiblePages(page, numPages, spread)[0] > 1;
}

export function hasNextPage(page: number, numPages: number, spread: boolean): boolean {
    const visible = visiblePages(page, numPages, spread);

    return visible[visible.length - 1] < numPages;
}

export function nextPage(page: number, numPages: number, spread: boolean): number {
    const visible = visiblePages(page, numPages, spread);

    return clampPage(visible[visible.length - 1] + 1, numPages);
}

export function prevPage(page: number, numPages: number, spread: boolean): number {
    const first = visiblePages(page, numPages, spread)[0];

    if (!spread) return clampPage(first - 1, numPages);

    // Back from the 2–3 spread lands on the lone cover.
    return first <= 2 ? 1 : first - 2;
}
