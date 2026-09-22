export interface BureauDesk {
    id: string;
    title: string;
    description: string;
    contactLines: string[];
    ctaLabel: string;
    ctaHref: string;
    featured?: boolean;
}

export interface DispatchStat {
    label: string;
    value: string;
    live?: boolean;
}

export interface DispatchRoutingOption {
    label: string;
    value: string;
}

export interface OfficeLocation {
    id: string;
    tag: string;
    timezoneLabel: string;
    city: string;
    region: string;
    address: string;
    chiefName: string;
    chiefContact: string;
    featured?: boolean;
}

/** Page-specific prop contract for `contact/index` (sitewide chrome comes from `SiteShared`). */
export interface ContactPageProps {
    eyebrow: string;
    heading: string;
    description: string;

    security: {
        heading: string;
        description: string;
        hotlineLabel: string;
        hotlineValue: string;
        emailLabel: string;
        emailValue: string;
        footnotes: string[];
    };

    desks: {
        eyebrow: string;
        heading: string;
        description: string;
        items: BureauDesk[];
    };

    dispatch: {
        eyebrow: string;
        heading: string;
        description: string;
        stats: DispatchStat[];
        routingOptions: DispatchRoutingOption[];
        consentLabel: string;
        submitLabel: string;
    };

    offices: {
        eyebrow: string;
        heading: string;
        sublabel: string;
        items: OfficeLocation[];
    };
}
