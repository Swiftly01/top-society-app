<?php

namespace App\Repositories\Eloquent;

use App\Enums\ArticleStatus;
use App\Enums\ContentType;
use App\Models\Article;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Override;

/**
 * @extends BaseRepository<Article>
 */
class ArticleRepository extends BaseRepository implements ArticleRepositoryInterface
{
    public function __construct(Article $model)
    {
        parent::__construct($model);
    }

    protected function query(): Builder
    {
        return parent::query()->with(['category', 'author', 'tags', 'featuredImage']);
    }

    public function suggest(string $term, int $limit = 5): Collection
{
    return $this->query()
        ->published()
        ->search($term)
        ->limit($limit)
        ->get();
}

    public function findBySlug(string $slug): ?Article
    {
        return $this->query()->where('slug', $slug)->first();
    }

    public function paginateByType(ContentType $type, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = $this->query()->where('type', $type->value);

        if (! empty($filters['status'])) {
            $query->where('status', $filters['status'] instanceof ArticleStatus ? $filters['status']->value : $filters['status']);
        }

        if (! empty($filters['category_id'])) {
            $query->where('category_id', $filters['category_id']);
        }

        if (! empty($filters['author_id'])) {
            $query->where('author_id', $filters['author_id']);
        }

        if (array_key_exists('is_featured', $filters)) {
            $query->where('is_featured', (bool) $filters['is_featured']);
        }

        if (array_key_exists('is_trending', $filters)) {
            $query->where('is_trending', (bool) $filters['is_trending']);
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(fn(Builder $q) => $q
                ->where('title', 'like', "%{$search}%")
                ->orWhere('excerpt', 'like', "%{$search}%"));
        }

        return $query->latest('published_at')->paginate($perPage);
    }

    public function published(ContentType $type, int $limit = 10): Collection
    {
        return $this->query()
            ->where('type', $type->value)
            ->published()
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }


   
    public function featured(ContentType $type, int $limit = 5): Collection
    {
        return $this->query()
            ->where('type', $type->value)
            ->published()
            ->featured()
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }

    public function trending(ContentType $type, int $limit = 5): Collection
    {
        return $this->query()
            ->where('type', $type->value)
            ->published()
            ->trending()
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }

     public function mostRead(ContentType $type, int $limit = 5): Collection
    {
        return $this->query()->where('type', $type->value)->published()->orderByDesc('views_count')->limit($limit)->get();
    }
    

    
    public function latestForHomeGrid(ContentType $type, ?string $categorySlug, int $limit = 4): Collection
    {
        return $this->query()
            ->where('type', $type->value)
            ->published()
            ->when(
                $categorySlug && $categorySlug !== 'all',
                fn (Builder $q) => $q->whereHas('category', fn (Builder $c) => $c->where('slug', $categorySlug)),
            )
            ->orderByDesc('published_at')
            ->limit($limit)
            ->get();
    }

    
    public function search(string $term, int $limit = 10, int $page = 1): Collection
    {
        return $this->query()
            ->published()
            ->search($term)
            ->orderByDesc('published_at')
            ->forPage($page, $limit)
            ->get();
    }

    public function countMatchingSearch(string $term): int
    {
        return $this->model->newQuery()
            ->published()
            ->search($term)
            ->count();
    }



    public function dueForPublishing(): Collection
    {
        return $this->query()
            ->where('status', ArticleStatus::Scheduled->value)
            ->whereNotNull('scheduled_for')
            ->where('scheduled_for', '<=', now())
            ->get();
    }

    public function slugExists(string $slug, ?int $exceptId = null): bool
    {
        return $this->model->newQuery()
            ->where('slug', $slug)
            ->when($exceptId, fn(Builder $q) => $q->where('id', '!=', $exceptId))
            ->exists();
    }
}
