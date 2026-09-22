export interface CuratorInfo {
    name: string;
    title: string;
    avatar?: string | null;
}

export interface PickArticle {
    id: number | string;
    badge: string;
    title: string;
    excerpt: string;
    author: string;
    image?: string | null;
    href: string;
}

/** Page-specific prop contract for `editors-picks/index` (sitewide chrome comes from `SiteShared`). */
export interface EditorsPicksPageProps {
    activeNav: string;
    title: string;
    description: string;
    curator: CuratorInfo;
    featured: PickArticle | null;
    secondary: PickArticle[];
}
