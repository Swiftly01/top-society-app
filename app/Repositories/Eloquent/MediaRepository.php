<?php

namespace App\Repositories\Eloquent;

use App\Enums\MediaCollection;
use App\Models\Media;
use App\Repositories\Contracts\MediaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends BaseRepository<Media>
 */
class MediaRepository extends BaseRepository implements MediaRepositoryInterface
{
    public function __construct(Media $model)
    {
        parent::__construct($model);
    }

    public function forMediable(Model $mediable, ?MediaCollection $collection = null): Collection
    {
        return $this->query()
            ->where('mediable_type', $mediable->getMorphClass())
            ->where('mediable_id', $mediable->getKey())
            ->when($collection, fn ($q) => $q->where('collection', $collection->value))
            ->orderBy('order')
            ->get();
    }

    public function findFeaturedFor(Model $mediable): ?Media
    {
        return $this->forMediable($mediable, MediaCollection::Featured)->first();
    }

    public function findForMediableAndCollection(Model $mediable, MediaCollection $collection): Collection
    {
        return $this->forMediable($mediable, $collection);
    }
}
