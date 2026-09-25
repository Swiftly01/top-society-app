import { Link } from '@inertiajs/react';
import { ArrowRight } from 'lucide-react';
import { ArticleCard } from '@/components/site/article-card';
import type { CategorySection } from '@/types/content';

interface CategoryNewsSectionsProps {
    sections: CategorySection[];
}

/**
 * Renders one titled card row per entry in `sections`. The list of
 * sections — and therefore how many render here — comes entirely from
 * the backend (HomeController::categorySections(), driven by the
 * category admin), so adding, renaming, reordering, or retiring a
 * category changes this part of the homepage with zero frontend
 * changes: no new component, no new prop, no new route.
 */
export function CategoryNewsSections({ sections }: CategoryNewsSectionsProps) {
    if (sections.length === 0) {
        return null;
    }

    return (
        <section className="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
            <div className="flex flex-col gap-12">
                {sections.map((section) => (
                    <CategoryNewsSection key={section.slug} section={section} />
                ))}
            </div>
        </section>
    );
}

function CategoryNewsSection({ section }: { section: CategorySection }) {
    return (
        <div>
            <div className="mb-6 flex items-center justify-between gap-4 border-b border-border pb-3">
                <h2 className="font-serif text-2xl font-bold text-foreground">{section.label}</h2>

                <Link
                    href={section.href}
                    className="inline-flex shrink-0 items-center gap-1 text-xs font-semibold tracking-wide text-red-600 uppercase hover:underline"
                >
                    View All
                    <ArrowRight className="size-3" />
                </Link>
            </div>

            <div className="grid gap-x-6 gap-y-8 sm:grid-cols-2 lg:grid-cols-3">
                {section.articles.map((article) => (
                    <ArticleCard key={article.id} article={article} />
                ))}
            </div>
        </div>
    );
}
