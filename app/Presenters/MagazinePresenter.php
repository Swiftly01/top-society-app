<?php

namespace App\Presenters;

use App\Models\Magazine;

/**
 * Transforms Magazine models into the exact prop shape the React
 * homepage expects — see resources/js/types/content.ts `Magazine`.
 */
class MagazinePresenter
{
    /**
     * @return array<string, mixed>
     */
    public static function toCard(Magazine $magazine): array
    {
        return [
            'id' => $magazine->id,
            'title' => $magazine->title,
            'issueLabel' => $magazine->issue_label,
            'description' => $magazine->description,
            'coverImage' => $magazine->coverImage?->url,
            'downloadHref' => $magazine->hasReadableResource() ? route('magazines.download', $magazine->slug) : null,
            // True when the click will leave the site (no PDF hosted here
            // yet, just a link) — tells the frontend to open a new tab and
            // swap the "Download PDF" label for something that doesn't
            // promise a download. See Magazine::hasReadableResource().
            'isExternal' => ! $magazine->hasPdf() && $magazine->hasExternalLink(),
            'publishedAt' => $magazine->published_at?->toIso8601String(),
        ];
    }
}
