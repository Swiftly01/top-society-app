import { Head, Link } from '@inertiajs/react';
import { NewsletterPlanCard } from '@/components/newsletter/newsletter-plan-card';
import { SectionHeader } from '@/components/site/section-header';
import { SiteFooter } from '@/components/site/site-footer';
import { Button } from '@/components/ui/button';
import type { NewsletterPageProps } from '@/types/newsletter';

export default function NewsletterIndex(props: NewsletterPageProps) {
    const { heading, subheading, plans, archive, archiveHref } = props;

    return (
        <>
            <Head title="Newsletters" />

            <div className="min-h-screen bg-background text-foreground">
                <SectionHeader showSubscribe={false} />

                <main className="mx-auto max-w-6xl px-4 py-14 sm:px-6 lg:px-8">
                    <div className="mx-auto max-w-3xl text-center">
                        <h1 className="font-serif text-4xl font-bold sm:text-5xl">{heading}</h1>
                        <p className="mt-4 text-base text-muted-foreground">{subheading}</p>
                    </div>

                    <div className="mt-10 grid gap-6 sm:grid-cols-3">
                        {plans.map((plan) => (
                            <NewsletterPlanCard key={plan.id} plan={plan} />
                        ))}
                    </div>

                    <div className="mx-auto mt-16 max-w-3xl border-t border-border pt-10">
                        <h2 className="text-center font-serif text-3xl font-bold">Newsletter Archive</h2>

                        <div className="mt-8 flex flex-col divide-y divide-border">
                            {archive.map((entry) => (
                                <div key={entry.id} className="flex flex-col gap-3 py-5 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <p className="text-xs text-muted-foreground">
                                            {entry.date} · {entry.newsletterName}
                                        </p>
                                        <h3 className="mt-1 font-serif text-lg font-bold">{entry.title}</h3>
                                    </div>
                                    <Button asChild variant="outline" size="sm" className="w-fit shrink-0 border-red-600 text-red-600 hover:bg-red-600 hover:text-white">
                                        <Link href={entry.href}>Read Now</Link>
                                    </Button>
                                </div>
                            ))}
                        </div>

                        <div className="mt-6 text-center">
                            <Link href={archiveHref} className="text-sm text-muted-foreground underline underline-offset-2 hover:text-foreground">
                                View Full Archive
                            </Link>
                        </div>
                    </div>
                </main>

                <SiteFooter />
            </div>
        </>
    );
}
