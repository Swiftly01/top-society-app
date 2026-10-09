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
    'type' => $advert->isVideo() ? 'video' : 'image',
    'src' => $advert->coverImage?->url,
    'href' => $advert->target_url ? route('adverts.click', $advert->id) : null,
];
    }
}
