export interface ArticleAuthor {
    name: string;
    title?: string | null;
    avatar?: string | null;
}

export interface ArticleTocItem {
    id: string;
    label: string;
}

export interface ArticleBlockParagraph {
    type: 'paragraph';
    html: string;
    /** Renders a large drop-cap on the first letter — used once, on the opening paragraph. */
    dropCap?: boolean;
}

export interface ArticleBlockHeading {
    type: 'heading';
    id: string;
    text: string;
}

export interface ArticleBlockQuote {
    type: 'quote';
    text: string;
}

export type ArticleBlock = ArticleBlockParagraph | ArticleBlockHeading | ArticleBlockQuote;

export interface TrendingLink {
    title: string;
    description: string;
    href: string;
}

export interface RelatedArticle {
    id: number | string;
    category: string;
    title: string;
    excerpt?: string | null;
    image?: string | null;
    href: string;
}

/** Page-specific prop contract for `articles/show` (sitewide chrome comes from `SiteShared`). */
export interface ArticlePageProps {
    category: string;
    categoryHref: string;
    title: string;
    publishedAt: string;
    author: ArticleAuthor;
    heroImage?: string | null;
    heroCaption?: string | null;
    toc: ArticleTocItem[];
    bodyHtml: string;
    trending: {
        heading: string;
        links: TrendingLink[];
    };
    readNext: RelatedArticle[];
}
