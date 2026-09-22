import type { Article } from '@/types/content';

export interface CategoryTopicLink {
    label: string;
    href: string;
}

/** Page-specific prop contract for `categories/show` (sitewide chrome comes from `SiteShared`). */
export interface CategoryPageProps {
    name: string;
    slug: string;
    /** Which section-nav label to highlight (e.g. "Tech") — may differ from the display `name` (e.g. "Technology"). */
    activeNav: string;
    breadcrumb: { label: string; href: string }[];
    featuredArticle: Article | null;
    secondaryArticles: Article[];
    briefing: {
        heading: string;
        description: string;
        ctaLabel: string;
    };
    topics: {
        heading: string;
        links: CategoryTopicLink[];
    };
}
