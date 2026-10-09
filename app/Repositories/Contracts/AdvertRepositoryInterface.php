<?php

namespace App\Repositories\Contracts;

use App\Enums\AdvertPlacement;
use App\Models\Advert;
use Illuminate\Database\Eloquent\Collection;

/**
 * @extends RepositoryInterface<Advert>
 */
interface AdvertRepositoryInterface extends RepositoryInterface
{
    /**
     * Adverts currently showing in one slot (active, inside their run
     * window, and with a file uploaded), in the admin's chosen order.
     * Capped — by default at the slot's own limit — so the homepage
     * payload stays small however many adverts exist.
     *
     * @return Collection<int, Advert>
     */
    public function live(AdvertPlacement $placement, ?int $limit = null): Collection;

    public function incrementClicks(Advert $advert): void;
}