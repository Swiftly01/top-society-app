<?php

namespace App\Presenters;

use App\Models\Advert;

/**
 * Transforms Advert models into the prop shape the homepage advert slider
 * expects — see resources/js/types/content.ts `Advert`.
 */
class AdvertPresenter
{
    /**
     * @return array<string, mixed>
     */
    public static function toSlide(Advert $advert): array
    {
        return [
            'id' => $advert->id,
            'title' => $advert->title,
            'image' => $advert->coverImage?->url,
            // Always our own tracking URL, never the advertiser's — the
            // click is counted, then AdvertController redirects onward.
            'href' => route('adverts.click', $advert->id),
        ];
    }
}
