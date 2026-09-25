<?php

namespace App\Presenters;

use App\Models\Newsletter;
use App\Models\NewsletterEdition;
use App\Support\ReadingTime;

class NewsletterPresenter
{
    /**
     * Matches resources/js/types/newsletter.ts `NewsletterPlan`.
     *
     * @return array<string, mixed>
     */
    public static function toPlan(Newsletter $newsletter): array
    {
        return [
            'id' => $newsletter->slug,
            'badge' => $newsletter->badge,
            'title' => $newsletter->name,
            'description' => $newsletter->description,
            'previewHref' => route('newsletter.preview', $newsletter->slug),
        ];
    }

    /**
     * Matches resources/js/types/newsletter.ts `NewsletterArchiveEntry`.
     *
     * @return array<string, mixed>
     */
    public static function toArchiveEntry(NewsletterEdition $edition): array
    {
        return [
            'id' => $edition->id,
            'date' => $edition->sent_at?->format('M d, Y') ?? '',
            'newsletterName' => $edition->newsletter->name,
            'title' => $edition->title,
            'href' => route('newsletter.archive.show', $edition->slug),
        ];
    }

    /**
     * The dedicated "read newsletter" page payload.
     *
     * @return array<string, mixed>
     */
    public static function toEditionShowPage(NewsletterEdition $edition): array
    {
        return [
            'newsletterName' => $edition->newsletter->name,
            'newsletterBadge' => $edition->newsletter->badge,
            'title' => $edition->title,
            'excerpt' => $edition->excerpt,
            'bodyHtml' => $edition->body,
            'sentAt' => $edition->sent_at?->format('F j, Y'),
            'readTime' => ReadingTime::estimate($edition->body),
        ];
    }
}