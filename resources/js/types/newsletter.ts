export interface NewsletterPlan {
    id: string;
    badge: string;
    title: string;
    description: string;
    previewHref: string;
}

export interface NewsletterArchiveEntry {
    id: number | string;
    date: string;
    newsletterName: string;
    title: string;
    href: string;
}

/** Page-specific prop contract for `newsletter/index` (sitewide chrome comes from `SiteShared`). */
export interface NewsletterPageProps {
    heading: string;
    subheading: string;
    plans: NewsletterPlan[];
    archive: NewsletterArchiveEntry[];
    archiveHref: string;
}
