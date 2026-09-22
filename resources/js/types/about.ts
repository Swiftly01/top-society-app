export interface AboutStat {
    value: string;
    label: string;
}

export interface AboutPillar {
    number: string;
    tag: string;
    title: string;
    description: string;
    footerNote: string;
}

export interface TeamMember {
    id: number | string;
    name: string;
    role: string;
    bio: string;
    photo?: string | null;
    href?: string;
}

export interface ChronicleEntry {
    year: string;
    tag: string;
    title: string;
    description: string;
}

/** Page-specific prop contract for `about/index` (sitewide chrome comes from `SiteShared`). */
export interface AboutPageProps {
    eyebrow: string;
    breadcrumbLabel: string;
    headingLead: string;
    headingEmphasis: string;
    quote: { text: string; attribution: string };
    manifesto: { heading: string; body: string }[];
    dossier: {
        label: string;
        title: string;
        body: string;
        meta: { label: string; value: string }[];
    };
    stats: {
        eyebrow: string;
        heading: string;
        sublabel: string;
        items: AboutStat[];
    };
    pillars: {
        eyebrow: string;
        heading: string;
        description: string;
        items: AboutPillar[];
    };
    team: {
        eyebrow: string;
        heading: string;
        sublabel: string;
        members: TeamMember[];
    };
    chronicle: {
        eyebrow: string;
        heading: string;
        sublabel: string;
        entries: ChronicleEntry[];
    };
}
