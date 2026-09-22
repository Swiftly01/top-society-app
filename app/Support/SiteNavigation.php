<?php

namespace App\Support;

/**
 * Builds the `site` object shared on every Inertia request (see
 * `HandleInertiaRequests::share()`). Centralizing nav/footer here means
 * every page controller gets the same masthead nav, section nav, and
 * footer for free — update it once and every page picks it up.
 *
 * When this becomes CMS-driven, this is the only class that needs to
 * change: swap the hardcoded arrays below for `Menu::main()->get()` /
 * `FooterSetting::current()` style queries and the shape stays identical.
 */
class SiteNavigation
{
    /**
     * @return array<string, mixed>
     */
    public static function shared(): array
    {
        return [
            'editionDate' => now()->format('l, F j, Y'),
            'editionLabel' => 'Nigeria & Global',
            'navItems' => self::navItems(),
            'sectionNavItems' => self::sectionNavItems(),
            'footer' => self::footer(),
        ];
    }

    /**
     * Full masthead nav — homepage and section fronts.
     *
     * @return array<int, array{label: string, href: string}>
     */
    public static function navItems(): array
    {
        return [
            ['label' => 'Home', 'href' => '/'],
            ['label' => 'Politics', 'href' => '/politics'],
            ['label' => 'The Nation', 'href' => '/the-nation'],
            ['label' => 'Business', 'href' => '/business'],
            ['label' => 'International', 'href' => '/international'],
            ['label' => 'Entertainment', 'href' => '/entertainment'],
            ['label' => 'Sport', 'href' => '/sport'],
            ['label' => 'Lifestyle', 'href' => '/lifestyle'],
        ];
    }

    /**
     * Short section nav — article, search, editor's picks, newsletter pages.
     *
     * @return array<int, array{label: string, href: string}>
     */
    public static function sectionNavItems(): array
    {
        return [
            ['label' => 'Politics', 'href' => '/politics'],
            ['label' => 'Tech', 'href' => '/categories/technology'],
            ['label' => 'Business', 'href' => '/business'],
            ['label' => 'Culture', 'href' => '/categories/culture'],
            ['label' => 'Science', 'href' => '/categories/science'],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function footer(): array
    {
        return [
            'brandName' => 'Top Society',
            'editionLabel' => 'Nigeria & Global',
            'brandTagline' => 'Premier cultural, political, and lifestyle authority delivering high-impact journalism, bespoke society dispatches, and inclusive analysis for discerning minds across Nigeria and the global diaspora.',
            'socialHandle' => '@topsocietyng',
            'linkGroups' => [
                [
                    'heading' => 'Content',
                    'links' => [
                        ['label' => 'Newsletter', 'href' => '/newsletter'],
                        ['label' => "Editor's Picks", 'href' => '/editors-picks'],
                        ['label' => 'Search', 'href' => '/search'],
                    //    ['label' => 'Videos & Documentaries', 'href' => '/videos'],
                    //    ['label' => 'Podcasts', 'href' => '/podcasts'],
                    //    ['label' => 'Photo Stories', 'href' => '/photo-stories'],
                    //    ['label' => 'Special Reports', 'href' => '/special-reports'],
                    ],
                ],
                [
                    'heading' => 'Company',
                    'links' => [
                        ['label' => 'About Us', 'href' => '/about'],
                        ['label' => 'Careers', 'href' => '/contact'],
                        ['label' => 'Contact Us', 'href' => '/contact'],
                    //    ['label' => 'Advertise With Us', 'href' => '/advertise'],
                    //    ['label' => 'Brand Studio', 'href' => '/brand-studio'],
                    ],
                ],
                [
                    'heading' => 'Legal',
                    'links' => [
                        ['label' => 'Privacy Policy', 'href' => '/legal/privacy-policy'],
                        ['label' => 'Terms of Service', 'href' => '/legal/terms-of-service'],
                       // ['label' => 'Ethics & Standards', 'href' => '/ethics'],
                       // ['label' => 'Editorial Archives', 'href' => '/archives'],
                    ],
                ],
            ],
            'newsletterHeading' => 'Subscribe',
            'newsletterCtaLabel' => 'Subscribe',
            'copyright' => '© '.now()->year.' Top Society. All Rights Reserved.',
            'editions' => ['Lagos', 'Abuja', 'London', 'New York'],
        ];
    }
}
