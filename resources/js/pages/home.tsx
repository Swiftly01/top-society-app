import { Head } from "@inertiajs/react";
import { DailyBriefing } from "@/components/home/daily-briefing";
import { HeroSection } from "@/components/home/hero-section";
import { LatestReporting } from "@/components/home/latest-reporting";
import { PartnershipDossier } from "@/components/home/partnership-dossier";
import { SiteFooter } from "@/components/site/site-footer";
import { SiteHeader } from "@/components/site/site-header";
import type { HomePageProps } from "@/types/content";

export default function Home(props: HomePageProps) {
    const {
        activeNav,
        featuredArticles,
        secondaryHeadlines,
        partnership,
        categoryFilters,
        activeCategory,
        latestArticles,
        mostRead,
        mostReadPromo,
        newsletter,
    } = props;

    return (
        <>
            <Head title="Home" />

            <div className="min-h-screen bg-background text-foreground">
                <SiteHeader activeNav={activeNav} />

                <main>
                    <HeroSection
                        featuredArticles={featuredArticles}
                        secondaryHeadlines={secondaryHeadlines}
                    />

                    {/* <PartnershipDossier partnership={partnership} /> */}
                    {partnership && (
                        <PartnershipDossier partnership={partnership} />
                    )}

                    <LatestReporting
                        articles={latestArticles}
                        categoryFilters={categoryFilters}
                        activeCategory={activeCategory}
                        mostRead={mostRead}
                        mostReadPromo={mostReadPromo}
                    />

                    <DailyBriefing newsletter={newsletter} />
                </main>

                <SiteFooter />
            </div>
        </>
    );
}
