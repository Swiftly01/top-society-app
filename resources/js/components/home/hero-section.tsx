import { Link } from '@inertiajs/react';
import { AdvertSlider } from '@/components/home/advert-slider';
import { HeroCarousel } from '@/components/home/hero-carousel';
import { MagazineSlider } from '@/components/home/magazine-slider';
import type { Advert, Article, FeaturedArticle, Magazine } from '@/types/content';

interface HeroSectionProps {
    featuredArticles: FeaturedArticle[];
    secondaryHeadlines: Article[];
    /** Empty hides the advert slider. */
    adverts?: Advert[];
    /** Empty hides the magazine slider. */
    magazines?: Magazine[];
    /** Empty hides the banner under the featured carousel. */
    bannerAdverts?: Advert[];
}

export function HeroSection({
    featuredArticles,
    secondaryHeadlines,
    adverts = [],
    magazines = [],
    bannerAdverts = [],
}: HeroSectionProps) {
    return (
        <section className="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div className="grid gap-8 lg:grid-cols-3">
                {/* Featured story carousel with the 728×90 leaderboard banner under it.
                    The banner keeps its exact 728:90 shape at any width, so a 728×90
                    (or 1456×180) image is never stretched or cropped. */}
                <div className="flex min-w-0 flex-col gap-8 lg:col-span-2">
                    <HeroCarousel articles={featuredArticles} />

                    <AdvertSlider
                        adverts={bannerAdverts}
                        ariaLabel="Sponsored banners"
                        aspectClassName="aspect-[728/90]"
                        intervalMs={6000}
                    />
                </div>

                {/* Right rail: adverts on top, magazine issues below, then headlines.
                    No inner scrollbar — it grows to its natural height. */}
                <div className="flex flex-col gap-5">
                    <AdvertSlider adverts={adverts} />
                    <MagazineSlider magazines={magazines} />

                    {secondaryHeadlines.length > 0 && (
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
                    )}
                </div>
            </div>
        </section>
    );
}