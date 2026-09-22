import { Head, Link } from '@inertiajs/react';
import { Bookmark, Printer, Share2 } from 'lucide-react';
import { ArticleBody } from '@/components/article/article-body';
import { ArticleCard } from '@/components/site/article-card';
import { ArticleMedia } from '@/components/site/article-media';
import { SectionHeader } from '@/components/site/section-header';
import { SiteFooter } from '@/components/site/site-footer';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Card, CardContent } from '@/components/ui/card';
import type { ArticlePageProps } from '@/types/article';

export default function ArticleShow(props: ArticlePageProps) {
    const { category, categoryHref, title, publishedAt, author, heroImage, heroCaption, toc, bodyHtml, trending, readNext } =
        props;

    return (
        <>
            <Head title={title} />

            <div className="min-h-screen bg-background text-foreground">
                <SectionHeader activeNav={category} />

                <article className="mx-auto max-w-4xl px-4 py-10 sm:px-6 lg:px-8">
                    <div className="flex flex-col items-center gap-4 text-center">
                        <Link
                            href={categoryHref}
                            className="rounded-sm border border-red-200 bg-red-50 px-2.5 py-1 text-[11px] font-semibold tracking-wide text-red-600 uppercase dark:border-red-900 dark:bg-red-950/40"
                        >
                            {category}
                        </Link>
                        <h1 className="font-serif text-3xl leading-tight font-bold sm:text-5xl">{title}</h1>
                        <div className="flex items-center gap-2 text-sm text-muted-foreground">
                            <span>{publishedAt}</span>
                            <span>·</span>
                            <Avatar className="size-6">
                                <AvatarImage src={author.avatar ?? undefined} alt={author.name} />
                                <AvatarFallback className="text-[10px]">
                                    {author.name
                                        .split(' ')
                                        .map((part) => part[0])
                                        .join('')}
                                </AvatarFallback>
                            </Avatar>
                            <span className="font-medium text-foreground">{author.name}</span>
                            {author.title && <span>· {author.title}</span>}
                        </div>
                    </div>
                </article>

                <div className="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
                    <ArticleMedia src={heroImage} alt={title} className="aspect-[16/8] w-full rounded-sm" />
                    {heroCaption && <p className="mt-2 text-right text-xs text-muted-foreground">{heroCaption}</p>}
                </div>

                <div className="mx-auto grid max-w-6xl gap-10 px-4 py-10 sm:px-6 lg:grid-cols-[auto_1fr_260px] lg:px-8">
                    {/* Share / bookmark / print rail */}
                    <div className="hidden flex-col gap-3 lg:flex">
                        <button type="button" aria-label="Share" className="rounded-full border border-border p-2 text-muted-foreground hover:text-red-600">
                            <Share2 className="size-4" />
                        </button>
                        <button type="button" aria-label="Bookmark" className="rounded-full border border-border p-2 text-muted-foreground hover:text-red-600">
                            <Bookmark className="size-4" />
                        </button>
                        <button type="button" aria-label="Print" className="rounded-full border border-border p-2 text-muted-foreground hover:text-red-600">
                            <Printer className="size-4" />
                        </button>
                    </div>

                    {/* Body */}
                    <div className="min-w-0">
                        {toc.length > 0 && (
                            <Card className="mb-8">
                                <CardContent className="py-4">
                                    <p className="mb-2 text-xs font-semibold tracking-widest text-muted-foreground uppercase">
                                        Contents
                                    </p>
                                    <ul className="flex flex-col gap-1.5">
                                        {toc.map((item) => (
                                            <li key={item.id}>
                                                <a href={`#${item.id}`} className="text-sm text-foreground hover:text-red-600 hover:underline">
                                                    {item.label}
                                                </a>
                                            </li>
                                        ))}
                                    </ul>
                                </CardContent>
                            </Card>
                        )}

                        <ArticleBody html={bodyHtml} />
                    </div>

                    {/* Trending sidebar */}
                    <aside className="lg:sticky lg:top-8 lg:h-fit">
                        <h2 className="mb-3 border-b border-border pb-2 text-xs font-semibold tracking-widest text-muted-foreground uppercase">
                            {trending.heading}
                        </h2>
                        <ul className="flex flex-col divide-y divide-border">
                            {trending.links.map((link) => (
                                <li key={link.href} className="py-3 first:pt-0">
                                    <Link href={link.href} className="group">
                                        <span className="text-sm font-semibold text-foreground group-hover:underline">
                                            {link.title}
                                        </span>
                                        <p className="mt-1 text-xs text-muted-foreground">{link.description}</p>
                                    </Link>
                                </li>
                            ))}
                        </ul>
                    </aside>
                </div>

                {readNext.length > 0 && (
                    <section className="border-t border-border bg-muted/30">
                        <div className="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
                            <h2 className="mb-6 font-serif text-2xl font-bold">Read Next</h2>
                            <div className="grid gap-x-6 gap-y-8 sm:grid-cols-3">
                                {readNext.map((article) => (
                                    <ArticleCard key={article.id} article={article} />
                                ))}
                            </div>
                        </div>
                    </section>
                )}

                <SiteFooter />
            </div>
        </>
    );
}
