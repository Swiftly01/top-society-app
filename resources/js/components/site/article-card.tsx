import { Link } from '@inertiajs/react';
import { ArticleMedia } from '@/components/site/article-media';
import type { Article } from '@/types/content';

interface ArticleCardProps {
    article: Article;
}

export function ArticleCard({ article }: ArticleCardProps) {
    return (
        <Link href={article.href} className="group flex flex-col">
            <ArticleMedia src={article.image} alt={article.title} className="aspect-[4/3] w-full rounded-sm" />
            <span className="mt-3 text-[11px] font-semibold tracking-wide text-red-600 uppercase">
                {article.category}
            </span>
            <h3 className="mt-1 font-serif text-base leading-snug font-bold text-foreground group-hover:underline">
                {article.title}
            </h3>
            {article.excerpt && (
                <p className="mt-1 line-clamp-2 text-sm text-muted-foreground">{article.excerpt}</p>
            )}
        </Link>
    );
}
