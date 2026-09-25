import { Head, Link } from '@inertiajs/react';
import { Pagination } from '@/components/site/pagination';
import { SectionHeader } from '@/components/site/section-header';
import { SiteFooter } from '@/components/site/site-footer';
import { Button } from '@/components/ui/button';
import type { NewsletterArchivePageProps } from '@/types/newsletter';

export default function NewsletterArchive({ entries, currentPage, lastPage }: NewsletterArchivePageProps) {
    return (
        <>
            <Head title="Newsletter Archive" />

            <div className="min-h-screen bg-background text-foreground">
                <SectionHeader showSubscribe={false} />

                <main className="mx-auto max-w-3xl px-4 py-14 sm:px-6 lg:px-8">
                    <div className="text-center">
                        <h1 className="font-serif text-4xl font-bold sm:text-5xl">Newsletter Archive</h1>
                        <p className="mt-4 text-base text-muted-foreground">
                            Every edition we've ever sent, in one place.
                        </p>
                    </div>

                    <div className="mt-10 flex flex-col divide-y divide-border">
                        {entries.map((entry) => (
                            <div key={entry.id} className="flex flex-col gap-3 py-5 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p className="text-xs text-muted-foreground">
                                        {entry.date} · {entry.newsletterName}
                                    </p>
                                    <h3 className="mt-1 font-serif text-lg font-bold">{entry.title}</h3>
                                </div>
                                <Button
                                    asChild
                                    variant="outline"
                                    size="sm"
                                    className="w-fit shrink-0 border-red-600 text-red-600 hover:bg-red-600 hover:text-white"
                                >
                                    <Link href={entry.href}>Read Now</Link>
                                </Button>
                            </div>
                        ))}

                        {entries.length === 0 && (
                            <p className="py-10 text-center text-sm text-muted-foreground">
                                No editions have been sent yet.
                            </p>
                        )}
                    </div>

                    {lastPage > 1 && (
                        <div className="mt-10">
                            <Pagination currentPage={currentPage} lastPage={lastPage} />
                        </div>
                    )}
                </main>

                <SiteFooter />
            </div>
        </>
    );
}
