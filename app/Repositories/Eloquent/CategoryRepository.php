<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends BaseRepository<Category>
 */
class CategoryRepository extends BaseRepository implements CategoryRepositoryInterface
{
    public function __construct(Category $model)
    {
        parent::__construct($model);
    }

    public function suggest(string $term, int $limit = 3): Collection
    {
        return $this->query()
            ->where('name', 'like', "%{$term}%")
            ->inPrimaryNav()
            ->orderBy('order')
            ->limit($limit)
            ->get();
    }

    public function primaryNav(): Collection
    {
        return $this->query()
            ->topLevel()
            ->inPrimaryNav()
            ->orderBy('order')
            ->get();
    }

    public function findBySlug(string $slug): ?Category
    {
        return $this->query()->where('slug', $slug)->first();
    }

    public function slugExists(string $slug, ?int $exceptId = null): bool
    {
        return $this->model->newQuery()
            ->where('slug', $slug)
            ->when($exceptId, fn(Builder $q) => $q->where('id', '!=', $exceptId))
            ->exists();
    }

    public function tree(): Collection
    {
        return $this->query()
            ->whereNull('parent_id')
            ->with('children')
            ->orderBy('order')
            ->get();
    }
}
