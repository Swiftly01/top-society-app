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
            // Public storage URL of the PDF, for the in-page reader. Null when
            // the issue only has an external link — the slider then shows the
            // cover and links out instead.
            'pdfUrl' => $magazine->hasPdf() ? $magazine->pdf?->url : null,
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
