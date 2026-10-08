<?php

namespace App\Services;

use App\Models\Advert;
use App\Repositories\Contracts\AdvertRepositoryInterface;

class AdvertService
{
    public function __construct(
        protected AdvertRepositoryInterface $adverts,
        protected MediaService $mediaService,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function create(array $attributes): Advert
    {
        return $this->adverts->create($attributes);
    }

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function update(Advert $advert, array $attributes): Advert
    {
        return $this->adverts->update($advert, $attributes);
    }

    /**
     * Adverts get swapped often, so — unlike most content here — delete
     * the uploaded creative too rather than leaving orphaned files in
     * storage.
     */
    public function delete(Advert $advert): bool
    {
        foreach ($advert->media as $media) {
            $this->mediaService->delete($media);
        }

        return $this->adverts->delete($advert);
    }

    public function recordClick(Advert $advert): void
    {
        $this->adverts->incrementClicks($advert);
    }
}
