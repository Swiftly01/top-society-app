import {
    ChevronLeft,
    ChevronRight,
    Download,
    ExternalLink,
} from "lucide-react";
import { ArticleMedia } from "@/components/site/article-media";
import { useAutoSlide } from "@/hooks/use-auto-slide";
import { cn } from "@/lib/utils";
import type { Magazine } from "@/types/content";

interface MagazineSliderProps {
    /** Newest first. */
    magazines: Magazine[];
    /** Milliseconds between auto-advances. Set to 0 to disable autoplay. */
    intervalMs?: number;
}

/**
 * Image-only slider of published magazine issues. Clicking a cover goes to
 * MagazineController::download, which streams the PDF (or redirects to the
 * external link if no PDF was uploaded). The corner icon is the only
 * visible hint; the title lives in aria-label for screen readers.
 */
export function MagazineSlider({
    magazines,
    intervalMs = 7000,
}: MagazineSliderProps) {
    const { index, goTo, next, prev, containerProps } = useAutoSlide({
        count: magazines.length,
        intervalMs,
    });

    if (magazines.length === 0) return null;

    return (
        <section
            className="group/magazines"
            role="region"
            aria-roledescription="carousel"
            aria-label="Magazine issues"
            {...containerProps}
        >
            <div className="relative h-56 overflow-hidden rounded-sm bg-muted">
                <div
                    className="flex size-full transition-transform duration-500 ease-in-out motion-reduce:transition-none"
                    style={{ transform: `translateX(-${index * 100}%)` }}
                >
                    {magazines.map((magazine, i) => (
                        <MagazineSlide
                            key={magazine.id}
                            magazine={magazine}
                            isActive={i === index}
                        />
                    ))}
                </div>

                {magazines.length > 1 && (
                    <>
                        <button
                            type="button"
                            onClick={prev}
                            aria-label="Previous issue"
                            className="absolute top-1/2 left-2 z-10 hidden -translate-y-1/2 rounded-full bg-black/40 p-1.5 text-white opacity-0 transition-opacity group-hover/magazines:opacity-100 hover:bg-black/60 focus-visible:opacity-100 sm:block"
                        >
                            <ChevronLeft className="size-4" />
                        </button>
                        <button
                            type="button"
                            onClick={next}
                            aria-label="Next issue"
                            className="absolute top-1/2 right-2 z-10 hidden -translate-y-1/2 rounded-full bg-black/40 p-1.5 text-white opacity-0 transition-opacity group-hover/magazines:opacity-100 hover:bg-black/60 focus-visible:opacity-100 sm:block"
                        >
                            <ChevronRight className="size-4" />
                        </button>
                    </>
                )}
            </div>

            {magazines.length > 1 && (
                <div className="mt-2 flex justify-center gap-1.5">
                    {magazines.map((magazine, i) => (
                        <button
                            key={magazine.id}
                            type="button"
                            onClick={() => goTo(i)}
                            aria-label={`Show issue ${i + 1} of ${magazines.length}: ${magazine.title}`}
                            aria-current={i === index}
                            className={cn(
                                "h-1.5 rounded-full transition-all",
                                i === index
                                    ? "w-5 bg-red-600"
                                    : "w-1.5 bg-border hover:bg-muted-foreground",
                            )}
                        />
                    ))}
                </div>
            )}
        </section>
    );
}

interface MagazineSlideProps {
    magazine: Magazine;
    isActive: boolean;
}

function MagazineSlide({ magazine, isActive }: MagazineSlideProps) {
    const { title, coverImage, downloadHref, isExternal } = magazine;

    const content = (
        <div className="relative h-full">
            <ArticleMedia
                src={coverImage}
                alt={title}
                className="aspect-[3/4] h-full"
            />
        </div>
    );

    const className =
        "group flex size-full shrink-0 items-center justify-center";

    if (!downloadHref) {
        return (
            <div className={className} aria-hidden={!isActive}>
                {content}
            </div>
        );
    }

    return (
        <a
            href={downloadHref}
            className={className}
            aria-hidden={!isActive}
            tabIndex={isActive ? 0 : -1}
            aria-label={
                isExternal ? `Read ${title}` : `Download ${title} as PDF`
            }
            {...(isExternal
                ? { target: "_blank", rel: "noopener noreferrer" }
                : {})}
        >
            {content}
        </a>
    );
}
