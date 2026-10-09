/**
 * Content types for the public-facing homepage.
 *
 * These mirror the array shapes returned by `App\Http\Controllers\HomeController`
 * (see `app/Http/Controllers/HomeController.php`). Keeping the shape centralized
 * here means the page and every section component share one contract — when the
 * backend swaps mock arrays for real `Article`/`Category` Eloquent models, only
 * the controller (and, if fields genuinely change, this file) needs to change.
 */

export interface NavItem {
    label: string;
    href: string;
}

export interface Article {
    id: number | string;
    title: string;
    excerpt?: string | null;
    category: string;
    href: string;
    /** Absolute or storage-relative URL. `null`/undefined renders a placeholder. */
    image?: string | null;
    author?: string | null;
    readTime?: string | null;
    publishedAt?: string | null;
}

export interface FeaturedArticle extends Article {
    /** Small overlay badge, e.g. "Technology". */
    badge: string;
}

export interface SponsoredArticle extends Article {
    /** e.g. "Sponsored by Aites" */
    sponsorLabel: string;
    /** True when this placement's media is a video rather than a static image. */
    isVideo?: boolean;
}

export interface MostReadArticle {
    id: number | string;
    rank: number;
    title: string;
    category: string;
    views: string;
    href: string;
}

export interface PromoCard {
    label: string;
    title: string;
    description: string;
    ctaLabel: string;
    href: string;
}

export interface PartnershipSection {
    tag: string;
    title: string;
    description: string;
    disclosureLabel: string;
    featured: SponsoredArticle & { collaborationLabel?: string | null };
    features: SponsoredArticle[];
}

export interface CategoryFilter {
    label: string;
    value: string;
}

/** One slide of the homepage advert slider. */
export interface Advert {
    id: number | string;
    /** Internal name; doubles as the image's alt text. */
    title: string;
    /** Absolute or storage-relative banner image URL. */
    image?: string | null;
    /** Our click-tracking URL — it counts the click, then redirects to the advertiser's site. Open in a new tab. Null when the advert has no link: render it as a plain, non-clickable banner. */
    href?: string | null;
}

/** A live magazine issue — one slide of the homepage magazine slider, click-to-download. */
export interface Magazine {
    id: number | string;
    title: string;
    issueLabel?: string | null;
    description?: string | null;
    /** Absolute or storage-relative cover image URL. */
    coverImage?: string | null;
    /** Public URL of the issue's PDF, read in the page by the magazine slider. Null for external-link-only issues. */
    pdfUrl?: string | null;
    /** Null when neither a PDF nor an external link is set — the card should hide its action affordance. */
    downloadHref?: string | null;
    /** True when downloadHref leaves the site (admin set a link, not a hosted PDF) — open in a new tab, don't promise a download. */
    isExternal?: boolean;
    publishedAt?: string | null;
}

/**
 * One category's "News by Category" card row. `articles` is capped
 * server-side (see HomeController::ARTICLES_PER_CATEGORY_SECTION); this
 * shape doesn't change as categories are added, renamed, or reordered in
 * the admin — the section list itself grows/shrinks instead.
 */
export interface CategorySection {
    label: string;
    slug: string;
    href: string;
    articles: Article[];
}

export interface NewsletterSection {
    title: string;
    description: string;
    ctaLabel: string;
}

export interface FooterLinkGroup {
    heading: string;
    links: NavItem[];
}

export interface FooterSection {
    brandName: string;
    brandTagline: string;
    editionLabel: string;
    socialHandle: string;
    linkGroups: FooterLinkGroup[];
    newsletterHeading: string;
    newsletterCtaLabel: string;
    copyright: string;
    editions: string[];
}

/**
 * Sitewide chrome shared on every page via `HandleInertiaRequests::share()`
 * (under the `site` key) — navigation, footer, and edition info live here
 * once instead of being repeated in every page controller.
 */
export interface SiteShared {
    editionDate: string;
    editionLabel: string;
    /** Full masthead nav (11 items) — used by `SiteHeader` on the homepage and section fronts. */
    navItems: NavItem[];
    /** Short section nav (5 items) — used by `SectionHeader` on inner pages. */
    sectionNavItems: NavItem[];
    footer: FooterSection;
}

/** Page-specific prop contract for the `home` Inertia page (sitewide chrome comes from `SiteShared`). */
export interface HomePageProps {
    activeNav: string;
    /** Rotates through the homepage hero carousel — 2 to 5 slides is the sweet spot. */
    featuredArticles: FeaturedArticle[];
    secondaryHeadlines: Article[];
    /** Live adverts in the admin's chosen order — empty hides the advert slider. */
    adverts: Advert[];
    /** Live issues, newest first — empty hides the magazine slider. */
    magazines: Magazine[];
    /** Null when no placement is currently featured and active — the whole Partnership Dossier section is hidden. */
    partnership: PartnershipSection | null;
    categoryFilters: CategoryFilter[];
    activeCategory: string;
    latestArticles: Article[];
    mostRead: MostReadArticle[];
    mostReadPromo: PromoCard;
    /** One row per category with published stories — see CategoryNewsSections. */
    categorySections: CategorySection[];
    newsletter: NewsletterSection;
}
