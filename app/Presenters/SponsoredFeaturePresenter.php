<?php

namespace App\Presenters;

use App\Models\SponsoredFeature;
use App\Support\ReadingTime;

/**
 * Mirrors ArticlePresenter's role for the sponsored-content domain: the
 * one place that knows how a SponsoredFeature model maps onto the
 * frontend's PartnershipSection / SponsoredArticle contract.
 */
class SponsoredFeaturePresenter
{
    /**
     * Matches resources/js/types/content.ts `SponsoredArticle`, used for
     * both the featured tile and the secondary list — the featured tile
     * additionally gets `collaborationLabel` via toFeatured() below.
     *
     * @return array<string, mixed>
     */
    public static function toCard(SponsoredFeature $feature): array
    {
        return [
            'id' => $feature->id,
            'title' => $feature->title,
            'excerpt' => $feature->excerpt,
            'category' => $feature->category_label ?? 'Partnership',
            'href' => self::hrefFor($feature),
            'image' => $feature->featuredImage?->url,
            'author' => null,
            'readTime' => $feature->body ? ReadingTime::estimate($feature->body) : null,
            'publishedAt' => $feature->updated_at?->toIso8601String(),
            'sponsorLabel' => $feature->displaySponsorLabel(),
            'isVideo' => $feature->hasVideo(),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function toFeatured(SponsoredFeature $feature): array
    {
        return [
            ...self::toCard($feature),
            'collaborationLabel' => $feature->collaboration_label,
        ];
    }

    /**
     * The full "read more" page payload — deliberately keeps
     * `disclosureLabel` and `sponsorLabel` front and center in the shape
     * itself (not just styled prominently in the component), so the
     * sponsored/ad nature of the page can never accidentally be dropped
     * by a future frontend refactor without a type error.
     *
     * @return array<string, mixed>
     */
    public static function toShowPage(SponsoredFeature $feature): array
    {
        return [
            'disclosureLabel' => $feature->disclosure_label,
            'sponsorName' => $feature->sponsor_name,
            'sponsorLabel' => $feature->displaySponsorLabel(),
            'collaborationLabel' => $feature->collaboration_label,
            'category' => $feature->category_label,
            'title' => $feature->title,
            'excerpt' => $feature->excerpt,
            'bodyHtml' => $feature->body,
            'heroImage' => $feature->featuredImage?->url,
            'videoUrl' => $feature->video?->url,
            'publishedAt' => $feature->updated_at?->format('F j, Y'),
            'readTime' => $feature->body ? ReadingTime::estimate($feature->body) : null,
        ];
    }

    protected static function hrefFor(SponsoredFeature $feature): string
    {
        return "/features/{$feature->slug}";
    }
}
