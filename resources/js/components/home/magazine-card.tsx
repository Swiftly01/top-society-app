import { Download } from 'lucide-react';
import { ArticleMedia } from '@/components/site/article-media';
import type { Magazine } from '@/types/content';

interface MagazineCardProps {
    magazine: Magazine;
}

/**
 * Sits above the secondary headlines list in the hero section. Clicking
 * the cover (or the button) downloads the issue's PDF — the link points
 * at MagazineController::download, which streams the file with a
 * Content-Disposition header, so this works as a plain <a> with no JS.
 */
export function MagazineCard({ magazine }: MagazineCardProps) {
    const { title, issueLabel, coverImage, downloadHref } = magazine;

    const content = (
        <>
            <div className="relative overflow-hidden rounded-sm">
                <ArticleMedia src={coverImage} alt={title} className="aspect-[3/4] w-full" />
                <span className="absolute top-3 left-3 rounded-sm bg-red-600 px-2 py-1 text-[10px] font-semibold tracking-wide text-white uppercase">
                    Latest Issue
                </span>
            </div>

            <div className="mt-3 flex items-start justify-between gap-2">
                <div>
                    {issueLabel && (
                        <span className="text-[11px] font-semibold tracking-wide text-red-600 uppercase">
                            {issueLabel}
                        </span>
                    )}
                    <h3 className="font-serif text-base leading-snug font-bold text-foreground">{title}</h3>
                </div>

                {downloadHref && (
                    <span className="mt-0.5 inline-flex shrink-0 items-center gap-1 text-[11px] font-semibold text-red-600">
                        <Download className="size-3.5" />
                        PDF
                    </span>
                )}
            </div>
        </>
    );

    if (!downloadHref) {
        return <div className="group flex flex-col gap-2 py-5 first:pt-0">{content}</div>;
    }

    return (
        <a
            href={downloadHref}
            className="group flex flex-col gap-2 py-5 first:pt-0"
            aria-label={`Download ${title} as PDF`}
        >
            {content}
        </a>
    );
}
