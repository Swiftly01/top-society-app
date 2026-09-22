/** Page-specific prop contract for `sponsored-features/show` (sitewide chrome comes from `SiteShared`). */
export interface SponsoredFeatureShowPageProps {
    /** e.g. "Sponsored", "Paid Partnership", "Advertisement" — always rendered prominently, never omitted. */
    disclosureLabel: string;
    sponsorName: string;
    sponsorLabel: string;
    collaborationLabel?: string | null;
    category?: string | null;
    title: string;
    excerpt?: string | null;
    /** HTML from the CMS — see the sanitization TODO on ArticlePresenter, which applies here too. */
    bodyHtml?: string | null;
    heroImage?: string | null;
    /** When present, the video renders in place of the hero image. */
    videoUrl?: string | null;
    publishedAt?: string | null;
    readTime?: string | null;
}
