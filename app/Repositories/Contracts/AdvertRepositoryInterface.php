<?php

namespace App\Repositories\Contracts;

use App\Models\Advert;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends RepositoryInterface<Advert>
 */
interface AdvertRepositoryInterface extends RepositoryInterface
{
    /**
     * Adverts currently on the site (active, inside their run window, and
     * with an image uploaded), in the admin's chosen order. Capped so the
     * homepage payload stays small however many adverts exist.
     *
     * @return Collection<int, Advert>
     */
    public function live(int $limit = 6): Collection;

    public function incrementClicks(Advert $advert): void;
}
