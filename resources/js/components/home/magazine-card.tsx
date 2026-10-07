import { Download, ExternalLink } from 'lucide-react';
import { ArticleMedia } from '@/components/site/article-media';
import type { Magazine } from '@/types/content';

interface MagazineCardProps {
    magazine: Magazine;
}

/**
 * Sits above the secondary headlines list in the hero section. Clicking
 * the cover (or the button) takes the reader to MagazineController::
 * download, which either streams the issue's PDF with a
 * Content-Disposition header (plain <a>, no JS needed) or — when the
 * admin only set an external link, no PDF hosted here — redirects
 * straight to it. `isExternal` (from MagazinePresenter) is the only
 * thing that differs between the two: which tab it opens in and what
 * the badge says, since the link itself always just works either way.
 */
export function MagazineCard({ magazine }: MagazineCardProps) {
    const { title, issueLabel, coverImage, downloadHref, isExternal } = magazine;

    const content = (
        <>
            <div className="relative overflow-hidden rounded-sm">
                {/* max-h-125 matches HeroCarousel's fixed min-h-125 (500px) so the
                    cover — a taller 3:4 portrait — can never grow past the carousel
                    it sits beside. aspect-[3/4] still governs sizing on narrower
                    columns where that ratio comes in under the cap. */}
                <ArticleMedia src={coverImage} alt={title} className="aspect-[3/4] w-full max-h-125" />
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
                        {isExternal ? (
                            <>
                                <ExternalLink className="size-3.5" />
                                Read
                            </>
                        ) : (
                            <>
                                <Download className="size-3.5" />
                                PDF
                            </>
                        )}
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
            aria-label={isExternal ? `Read ${title}` : `Download ${title} as PDF`}
            {...(isExternal ? { target: '_blank', rel: 'noopener noreferrer' } : {})}
        >
            {content}
        </a>
    );
}
