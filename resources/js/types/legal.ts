export type LegalBlock =
    | { type: 'paragraph'; html: string }
    | {
          type: 'notice';
          tone: 'default' | 'dark';
          label?: string;
          title?: string;
          body?: string;
          columns?: { title: string; body: string }[];
      }
    | { type: 'list'; items: string[] };

export interface LegalSection {
    id: string;
    /** e.g. "01" — shown for numbered documents like Terms of Service. */
    number?: string;
    heading: string;
    blocks: LegalBlock[];
}

export interface LegalMetaItem {
    label: string;
    value: string;
}

/** Page-specific prop contract for `legal/show` (sitewide chrome comes from `SiteShared`). */
export interface LegalDocumentPageProps {
    breadcrumb: { label: string; href: string }[];
    eyebrow: string;
    title: string;
    description?: string;
    meta: LegalMetaItem[];
    tocHeading: string;
    toc: { id: string; label: string }[];
    sidebarNotice?: { title: string; body: string };
    downloadHref?: string;
    downloadLabel?: string;
    printLabel?: string;
    sections: LegalSection[];
    acknowledgement?: { label: string; ctaLabel: string };
}
