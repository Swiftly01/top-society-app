<?php

namespace App\Services;

use App\Presenters\ArticlePresenter;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\TagRepositoryInterface;
use Illuminate\Support\Facades\Cache;

class SearchService
{
    public function __construct(
        protected ArticleRepositoryInterface $articles,
        protected CategoryRepositoryInterface $categories,
        protected TagRepositoryInterface $tags,
    ) {}

    /**
     * Deliberately short TTL (60s): stale-for-a-minute autosuggest
     * results are an acceptable trade-off for meaningfully cutting DB
     * load on popular search terms, and nothing here is safety-critical
     * enough to need immediate invalidation on publish.
     *
     * @return array<string, mixed>
     */
    public function suggest(string $term): array
    {
        $cacheKey = 'search:suggest:'.md5(mb_strtolower(trim($term)));

        return Cache::remember($cacheKey, now()->addSeconds(60), function () use ($term) {
            return [
                'articles' => $this->articles->suggest($term, 5)
                    ->map(fn ($article) => ArticlePresenter::toSuggestion($article))
                    ->all(),
                'categories' => $this->categories->suggest($term, 3)
                    ->map(fn ($category) => [
                        'id' => $category->id,
                        'label' => $category->name,
                        'href' => "/categories/{$category->slug}",
                    ])
                    ->all(),
                'tags' => $this->tags->suggest($term, 5)
                    ->map(fn ($tag) => [
                        'id' => $tag->id,
                        'label' => $tag->name,
                        'href' => '/search?q='.urlencode($tag->name),
                    ])
                    ->all(),
            ];
        });
    }
}