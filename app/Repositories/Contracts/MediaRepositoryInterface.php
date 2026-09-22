<?php

namespace App\Repositories\Contracts;

use App\Enums\MediaCollection;
use App\Models\Media;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends RepositoryInterface<Media>
 */
interface MediaRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Collection<int, Media>
     */
    public function forMediable(Model $mediable, ?MediaCollection $collection = null): Collection;

    public function findFeaturedFor(Model $mediable): ?Media;

    /**
     * Every media record for a given owner + collection — the MediaService
     * uses this before replacing a single-slot collection like `featured`:
     * it deletes each returned record's file from storage, then deletes
     * the record itself. Deletion is intentionally *not* done here, since
     * this repository has no knowledge of the storage layer.
     *
     * @return Collection<int, Media>
     */
    public function findForMediableAndCollection(Model $mediable, MediaCollection $collection): Collection;
}
