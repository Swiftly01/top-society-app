import { Link } from '@inertiajs/react';
import { HeroCarousel } from '@/components/home/hero-carousel';
import type { Article, FeaturedArticle } from '@/types/content';

interface HeroSectionProps {
    featuredArticles: FeaturedArticle[];
    secondaryHeadlines: Article[];
}

export function HeroSection({ featuredArticles, secondaryHeadlines }: HeroSectionProps) {
    return (
        <section className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div className="grid gap-8 lg:grid-cols-3">
                {/* Featured story carousel */}
                <div className="lg:col-span-2">
                    <HeroCarousel articles={featuredArticles} />
                </div>

                {/* Secondary headlines */}
                <div className="flex flex-col divide-y divide-border">
                    {secondaryHeadlines.map((article) => (
                        <Link
                            key={article.id}
                            href={article.href}
                            className="group flex flex-col gap-2 py-5 first:pt-0"
                        >
                            <span className="text-xs font-semibold tracking-wide text-red-600 uppercase">
                                {article.category}
                            </span>
                            <h2 className="font-serif text-lg leading-snug font-bold text-foreground group-hover:underline">
                                {article.title}
                            </h2>
                            {article.excerpt && (
                                <p className="line-clamp-2 text-sm text-muted-foreground">{article.excerpt}</p>
                            )}
                            {article.readTime && (
                                <span className="text-xs text-muted-foreground">{article.readTime}</span>
                            )}
                        </Link>
                    ))}
                </div>
            </div>
        </section>
    );
}
