import { Head } from '@inertiajs/react';
import { Megaphone } from 'lucide-react';
import { ArticleBody } from '@/components/article/article-body';
import { ArticleMedia } from '@/components/site/article-media';
import { SectionHeader } from '@/components/site/section-header';
import { SiteFooter } from '@/components/site/site-footer';
import type { SponsoredFeatureShowPageProps } from '@/types/sponsored-feature';

export default function SponsoredFeatureShow(props: SponsoredFeatureShowPageProps) {
    const {
        disclosureLabel,
        sponsorName,
        sponsorLabel,
        collaborationLabel,
        category,
        title,
        excerpt,
        bodyHtml,
        heroImage,
        videoUrl,
        publishedAt,
        readTime,
    } = props;

    return (
        <>
            <Head title={`${title} — ${disclosureLabel}`} />

            <div className="min-h-screen bg-background text-foreground">
                {/* Unmissable disclosure bar — deliberately its own element above
                    the masthead area, not just a badge inline with the headline. */}
                <div className="bg-neutral-950 py-2 text-center text-white">
                    <span className="inline-flex items-center gap-1.5 text-xs font-semibold tracking-widest uppercase">
                        <Megaphone className="size-3.5" />
                        {disclosureLabel} Content — Produced in Partnership with {sponsorName}
                    </span>
                </div>

                <SectionHeader activeNav={category ?? undefined} />

                <article className="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
                    <div className="flex flex-col items-center gap-3 text-center">
                        <span className="rounded-sm border border-red-200 bg-red-50 px-2.5 py-1 text-[11px] font-semibold tracking-wide text-red-600 uppercase dark:border-red-900 dark:bg-red-950/40">
                            {disclosureLabel}
                        </span>
                        {category && (
                            <span className="text-xs font-semibold tracking-widest text-muted-foreground uppercase">
                                {category}
                            </span>
                        )}
                        <h1 className="font-serif text-3xl leading-tight font-bold sm:text-4xl">{title}</h1>
                        {excerpt && <p className="max-w-xl text-base text-muted-foreground">{excerpt}</p>}
                        <div className="flex items-center gap-2 text-sm text-muted-foreground">
                            <span className="font-medium text-foreground">{sponsorLabel}</span>
                            {publishedAt && <span>· {publishedAt}</span>}
                            {readTime && <span>· {readTime}</span>}
                        </div>
                    </div>
                </article>

                <div className="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
                    {videoUrl ? (
                        <video
                            src={videoUrl}
                            controls
                            className="aspect-video w-full rounded-sm bg-black"
                        />
                    ) : (
                        <ArticleMedia src={heroImage} alt={title} className="aspect-[16/8] w-full rounded-sm" />
                    )}
                    {collaborationLabel && (
                        <p className="mt-3 text-center text-xs font-medium text-muted-foreground">{collaborationLabel}</p>
                    )}
                </div>

                {bodyHtml && (
                    <div className="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
                        <ArticleBody html={bodyHtml} />
                    </div>
                )}

                <div className="mx-auto max-w-3xl px-4 pb-16 sm:px-6 lg:px-8">
                    <p className="rounded-sm border border-border bg-muted/40 p-4 text-xs text-muted-foreground">
                        This is {disclosureLabel.toLowerCase()} content. It was produced in partnership with{' '}
                        {sponsorName} and did not involve the TOP SOCIETY editorial newsroom.
                    </p>
                </div>

                <SiteFooter />
            </div>
        </>
    );
}
