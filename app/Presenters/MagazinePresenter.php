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
            'downloadHref' => $magazine->hasPdf() ? route('magazines.download', $magazine->slug) : null,
            'publishedAt' => $magazine->published_at?->toIso8601String(),
        ];
    }
}
