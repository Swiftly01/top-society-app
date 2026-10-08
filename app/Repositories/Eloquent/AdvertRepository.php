<?php

namespace App\Repositories\Eloquent;

use App\Models\Advert;
use App\Repositories\Contracts\AdvertRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends BaseRepository<Advert>
 */
class AdvertRepository extends BaseRepository implements AdvertRepositoryInterface
{
    public function __construct(Advert $model)
    {
        parent::__construct($model);
    }

    protected function query(): Builder
    {
        return parent::query()->with('coverImage');
    }

    public function live(int $limit = 6): Collection
    {
        return $this->query()
            ->live()
            ->whereHas('coverImage')
            ->ordered()
            ->limit($limit)
            ->get();
    }

    public function incrementClicks(Advert $advert): void
    {
        // Atomic UPDATE ... SET clicks_count = clicks_count + 1, so
        // concurrent clicks never overwrite each other.
        $advert->increment('clicks_count');
    }
}
