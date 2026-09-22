import { router } from '@inertiajs/react';
import { ArticleCard } from '@/components/site/article-card';
import { MostReadSidebar } from '@/components/home/most-read-sidebar';
import { cn } from '@/lib/utils';
import type { Article, CategoryFilter, MostReadArticle, PromoCard } from '@/types/content';

interface LatestReportingProps {
    articles: Article[];
    categoryFilters: CategoryFilter[];
    activeCategory: string;
    mostRead: MostReadArticle[];
    mostReadPromo: PromoCard;
}

export function LatestReporting({
    articles,
    categoryFilters,
    activeCategory,
    mostRead,
    mostReadPromo,
}: LatestReportingProps) {
    return (
        <section className="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <div className="grid gap-10 lg:grid-cols-3">
                <div className="lg:col-span-2">
                    <div className="mb-6 flex flex-wrap items-center justify-between gap-4">
                        <h2 className="font-serif text-2xl font-bold text-foreground">Latest Reporting</h2>

                        {/* Category filters. Uses partial reloads so switching category only
                            re-fetches `latestArticles` + `activeCategory`, not the whole page. */}
                        <div className="flex gap-1 overflow-x-auto rounded-full bg-muted p-1">
                            {categoryFilters.map((filter) => (
                                <button
                                    key={filter.value}
                                    type="button"
                                    onClick={() =>
                                        router.get(
                                            '/',
                                            { category: filter.value },
                                            { preserveScroll: true, preserveState: true, only: ['latestArticles', 'activeCategory'] },
                                        )
                                    }
                                    className={cn(
                                        'shrink-0 rounded-full px-3 py-1.5 text-xs font-semibold whitespace-nowrap transition-colors',
                                        filter.value === activeCategory
                                            ? 'bg-red-600 text-white'
                                            : 'text-muted-foreground hover:text-foreground',
                                    )}
                                >
                                    {filter.label}
                                </button>
                            ))}
                        </div>
                    </div>

                    <div className="grid gap-x-6 gap-y-8 sm:grid-cols-2">
                        {articles.map((article) => (
                            <ArticleCard key={article.id} article={article} />
                        ))}
                    </div>

                    {articles.length === 0 && (
                        <p className="py-10 text-center text-sm text-muted-foreground">
                            No stories in this category yet.
                        </p>
                    )}
                </div>

                <MostReadSidebar items={mostRead} promo={mostReadPromo} />
            </div>
        </section>
    );
}
