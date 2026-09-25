import { Head } from '@inertiajs/react';
import { ArticleBody } from '@/components/article/article-body';
import { SectionHeader } from '@/components/site/section-header';
import { SiteFooter } from '@/components/site/site-footer';

interface NewsletterEditionPageProps {
    newsletterName: string;
    newsletterBadge: string;
    title: string;
    excerpt?: string | null;
    bodyHtml: string;
    sentAt?: string | null;
    readTime?: string | null;
}

export default function NewsletterEdition(props: NewsletterEditionPageProps) {
    const { newsletterName, newsletterBadge, title, excerpt, bodyHtml, sentAt, readTime } = props;

    return (
        <>
            <Head title={title} />

            <div className="min-h-screen bg-background text-foreground">
                <SectionHeader showSubscribe={false} />

                <article className="mx-auto max-w-3xl px-4 py-10 sm:px-6 lg:px-8">
                    <div className="flex flex-col items-center gap-3 text-center">
                        <span className="rounded-sm border border-red-200 bg-red-50 px-2.5 py-1 text-[11px] font-semibold tracking-wide text-red-600 uppercase dark:border-red-900 dark:bg-red-950/40">
                            {newsletterBadge} · {newsletterName}
                        </span>
                        <h1 className="font-serif text-3xl leading-tight font-bold sm:text-4xl">{title}</h1>
                        {excerpt && <p className="max-w-xl text-base text-muted-foreground">{excerpt}</p>}
                        <div className="flex items-center gap-2 text-sm text-muted-foreground">
                            {sentAt && <span>{sentAt}</span>}
                            {readTime && <span>· {readTime}</span>}
                        </div>
                    </div>
                </article>

                <div className="mx-auto max-w-3xl px-4 pb-16 sm:px-6 lg:px-8">
                    <ArticleBody html={bodyHtml} />
                </div>

                <SiteFooter />
            </div>
        </>
    );
}