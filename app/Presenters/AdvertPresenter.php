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
            // Our own tracking URL, never the advertiser's — the click is
            // counted, then AdvertController redirects onward. Null when the
            // advert has no link, so the slider renders it unclickable.
            'href' => $advert->target_url ? route('adverts.click', $advert->id) : null,
        ];
    }
}
