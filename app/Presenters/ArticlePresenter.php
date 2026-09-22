<?php

namespace App\Presenters;

use App\Models\Article;
use App\Support\HtmlHeadingExtractor;
use App\Support\ReadingTime;

/**
 * Transforms Article models into the exact prop shapes the React pages
 * expect — the one place that translates "how content is stored" into
 * "what the frontend contract says", so a schema change touches one
 * file, not five controllers.
 */
class ArticlePresenter
{
    /**
     * Matches resources/js/types/content.ts `Article`.
     *
     * @return array<string, mixed>
     */
    public static function toCard(Article $article): array
    {
        return [
            'id' => $article->id,
            'title' => $article->title,
            'excerpt' => $article->excerpt,
            'category' => $article->category?->name ?? 'Uncategorized',
            'href' => self::hrefFor($article),
            'image' => $article->featuredImage?->url,
            'author' => $article->author?->name,
            'readTime' => ReadingTime::estimate($article->body),
            'publishedAt' => $article->published_at?->toIso8601String(),
        ];
    }

    /**
     * Matches resources/js/types/content.ts `FeaturedArticle` — a card
     * plus the hero carousel's overlay badge.
     *
     * @return array<string, mixed>
     */
    public static function toFeatured(Article $article): array
    {
        return [
            ...self::toCard($article),
            'badge' => $article->category?->name ?? 'Featured',
        ];
    }

    /**
     * Matches resources/js/types/content.ts `MostReadArticle`. `$rank` is
     * the item's 1-based position in whatever list it's rendered in — not
     * a stored value, since it's only meaningful relative to its
     * neighbors in a given list (and changes any time view counts do).
     *
     * @return array<string, mixed>
     */
    public static function toMostRead(Article $article, int $rank): array
    {
        return [
            'id' => $article->id,
            'rank' => $rank,
            'title' => $article->title,
            'category' => $article->category?->name ?? 'Uncategorized',
            'views' => self::formatViews($article->views_count),
            'href' => self::hrefFor($article),
        ];
    }

    /**
     * Matches resources/js/types/search.ts `SearchResult`.
     *
     * @return array<string, mixed>
     */
    public static function toSearchResult(Article $article): array
    {
        return [
            'id' => $article->id,
            'type' => 'Article',
            'date' => $article->published_at?->format('M j, Y') ?? '',
            'title' => $article->title,
            'excerpt' => $article->excerpt ?? '',
            'byline' => $article->author ? "By {$article->author->name}" : '',
            'image' => $article->featuredImage?->url,
            'href' => self::hrefFor($article),
        ];
    }

    /**
     * Matches resources/js/types/category.ts / editors-picks.ts —
     * anywhere that just needs {id, category, title, excerpt, image, href}.
     *
     * @return array<string, mixed>
     */
    public static function toRelated(Article $article): array
    {
        return [
            'id' => $article->id,
            'category' => $article->category?->name ?? 'Uncategorized',
            'title' => $article->title,
            'excerpt' => $article->excerpt,
            'image' => $article->featuredImage?->url,
            'href' => self::hrefFor($article),
        ];
    }

    /**
     * The full `articles/show` page payload — matches
     * resources/js/types/article.ts `ArticlePageProps` minus the
     * `trending`/`readNext` keys, which need a sibling-article query the
     * caller (ArticleController) is better positioned to build.
     *
     * @return array<string, mixed>
     */
    public static function toShowPage(Article $article): array
    {
        // TODO: run $article->body through an HTML sanitizer (e.g.
        // mews/purifier) before this reaches the frontend. Filament's
        // RichEditor is a trusted-admin surface for Editors/Administrators,
        // but the Author role can also submit body HTML, so treat it as
        // untrusted input until a sanitizer sits in this pipeline.
        ['html' => $bodyHtml, 'toc' => $toc] = HtmlHeadingExtractor::extract($article->body);

        return [
            'category' => $article->category?->name ?? 'Uncategorized',
            'categoryHref' => $article->category ? "/categories/{$article->category->slug}" : '/',
            'title' => $article->title,
            'publishedAt' => $article->published_at?->format('F j, Y') ?? '',
            'author' => [
                'name' => $article->author?->name ?? 'TOP SOCIETY Staff',
                'title' => null,
                'avatar' => null,
            ],
            'heroImage' => $article->featuredImage?->url,
            'heroCaption' => $article->featuredImage?->alt_text,
            'toc' => $toc,
            'bodyHtml' => $bodyHtml,
        ];
    }

    protected static function hrefFor(Article $article): string
    {
        return "/articles/{$article->slug}";
    }

    protected static function formatViews(int $count): string
    {
        if ($count >= 1_000_000) {
            return round($count / 1_000_000, 1).'M views';
        }

        if ($count >= 1_000) {
            return round($count / 1_000, 1).'k views';
        }

        return "{$count} views";
    }
}
