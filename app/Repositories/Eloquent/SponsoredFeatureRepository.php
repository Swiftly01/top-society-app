<?php

namespace App\Repositories\Eloquent;

use App\Models\SponsoredFeature;
use App\Repositories\Contracts\SponsoredFeatureRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends BaseRepository<SponsoredFeature>
 */
class SponsoredFeatureRepository extends BaseRepository implements SponsoredFeatureRepositoryInterface
{
    public function __construct(SponsoredFeature $model)
    {
        parent::__construct($model);
    }

    protected function query(): Builder
    {
         $query = parent::query()->with(['featuredImage', 'video']);
      //   dd($query);
         return $query;
    }

    public function findBySlug(string $slug): ?SponsoredFeature
    {
        return $this->query()->where('slug', $slug)->first();
    }

    public function slugExists(string $slug, ?int $exceptId = null): bool
    {
        return $this->model->newQuery()
            ->where('slug', $slug)
            ->when($exceptId, fn (Builder $q) => $q->where('id', '!=', $exceptId))
            ->exists();
    }

    public function activeFeatured(): ?SponsoredFeature
    {
        return $this->query()
            ->active()
            ->featured()
            ->latest('updated_at')
            ->first();
    }

    public function activeSecondary(int $limit = 2): Collection
    {
        $query = $this->query()
            ->active()
            ->secondary()
            ->orderBy('display_order')
            ->latest('updated_at')
            ->limit($limit)
            ->get();

          //  dd($query);
    return $query;
    }

    public function allFeatured(?int $exceptId = null): Collection
    {
        return $this->model->newQuery()
            ->featured()
            ->when($exceptId, fn (Builder $q) => $q->where('id', '!=', $exceptId))
            ->get();
    }
}
