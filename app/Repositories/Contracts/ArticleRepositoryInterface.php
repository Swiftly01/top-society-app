<?php

namespace App\Repositories\Contracts;

use App\Enums\ArticleStatus;
use App\Enums\ContentType;
use App\Models\Article;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends RepositoryInterface<Article>
 */
interface ArticleRepositoryInterface extends RepositoryInterface
{
    public function findBySlug(string $slug): ?Article;

    /**
     * @param  array<string, mixed>  $filters  Supported keys: status, category_id, author_id, is_featured, is_trending, search
     */
    public function paginateByType(ContentType $type, array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * @return Collection<int, Article>
     */
    public function published(ContentType $type, int $limit = 10): Collection;

    /**
     * @return Collection<int, Article>
     */
    public function featured(ContentType $type, int $limit = 5): Collection;

    /**
     * @return Collection<int, Article>
     */
    public function trending(ContentType $type, int $limit = 5): Collection;

    /**
     * @return Collection<int, Article>
     */
    public function mostRead(ContentType $type, int $limit = 5): Collection;

    /**
     * @return Collection<int, Article>
     */
    public function latestForHomeGrid(ContentType $type, ?string $categorySlug, int $limit = 4): Collection;


    /**
     * @return Collection<int, Article>
     */
    public function search(string $term, int $limit = 10, int $page = 1): Collection;

    public function countMatchingSearch(string $term): int;

    /**
     * Articles whose `scheduled_for` time has arrived but are still sitting
     * in `scheduled` status — what the publish-scheduled-content command
     * sweeps and flips to `published`.
     *
     * @return Collection<int, Article>
     */
    public function dueForPublishing(): Collection;

    public function slugExists(string $slug, ?int $exceptId = null): bool;

    /**
     * @return Collection<int, Article>
     */
    public function suggest(string $term, int $limit = 5): Collection;
}
