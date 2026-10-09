<?php

namespace App\Enums;

/**
 * The advert slots on the site. Every advert belongs to exactly one, so a
 * new slot is one case here, one prop in the page controller and one
 * component — no new table, no new admin screen, no new migration.
 */
enum AdvertPlacement: string
{
    /** The small slider above the magazine, in the homepage hero's right rail. */
    case HeroRail = 'hero_rail';

    /** The full-width banner between "Latest Reporting" and the category cards. */
    case HomeBanner = 'home_banner';

    public function label(): string
    {
        return match ($this) {
            self::HeroRail => 'Homepage — hero sidebar',
            self::HomeBanner => 'Homepage — banner below Latest Reporting',
        };
    }

    /**
     * Most slides the slot ever sends to the browser. A server-side cap, so
     * the homepage payload stays bounded however many adverts exist.
     */
    public function limit(): int
    {
        return match ($this) {
            self::HeroRail => 6,
            self::HomeBanner => 8,
        };
    }

    /**
     * Shown under the upload field so admins export the right shape.
     */
    public function recommendedSize(): string
    {
        return match ($this) {
            self::HeroRail => '1200 × 675 px (16:9)',
            self::HomeBanner => '1200 × 280 px (30:7 wide strip)',
        };
    }
}
