import { Head } from '@inertiajs/react';
import { BureauDeskGrid } from '@/components/contact/bureau-desk-grid';
import { DispatchForm } from '@/components/contact/dispatch-form';
import { OfficeNetworkGrid } from '@/components/contact/office-network-grid';
import { SiteFooter } from '@/components/site/site-footer';
import { SiteHeader } from '@/components/site/site-header';
import type { ContactPageProps } from '@/types/contact';

export default function ContactIndex(props: ContactPageProps) {
    const { eyebrow, heading, description, security, desks, dispatch, offices } = props;

    return (
        <>
            <Head title="Contact" />

            <div className="min-h-screen bg-background text-foreground">
                <SiteHeader activeNav="Home" />

                <main>
                    <section className="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
                        <span className="text-[11px] font-semibold tracking-widest text-red-600 uppercase">
                            {eyebrow}
                        </span>

                        <div className="mt-2 grid gap-8 lg:grid-cols-[1fr_320px]">
                            <div>
                                <h1 className="font-serif text-4xl leading-tight font-bold sm:text-5xl">{heading}</h1>
                                <p className="mt-4 max-w-xl text-base text-muted-foreground">{description}</p>
                            </div>

                            <aside className="h-fit rounded-sm bg-neutral-950 p-5 text-white">
                                <p className="text-[10px] font-semibold tracking-widest text-red-500 uppercase">
                                    {security.heading}
                                </p>
                                <p className="mt-2 text-sm text-neutral-400">{security.description}</p>

                                <div className="mt-4 border-t border-neutral-800 pt-4">
                                    <p className="text-[10px] tracking-widest text-neutral-500 uppercase">
                                        {security.hotlineLabel}
                                    </p>
                                    <p className="text-sm font-semibold">{security.hotlineValue}</p>
                                </div>
                                <div className="mt-3">
                                    <p className="text-[10px] tracking-widest text-neutral-500 uppercase">
                                        {security.emailLabel}
                                    </p>
                                    <p className="text-sm font-semibold text-red-500">{security.emailValue}</p>
                                </div>

                                <ul className="mt-4 flex flex-col gap-1 border-t border-neutral-800 pt-4 text-[11px] text-neutral-500">
                                    {security.footnotes.map((note) => (
                                        <li key={note}>{note}</li>
                                    ))}
                                </ul>
                            </aside>
                        </div>
                    </section>

                    <div className="border-y border-border bg-muted/30">
                        <BureauDeskGrid
                            eyebrow={desks.eyebrow}
                            heading={desks.heading}
                            description={desks.description}
                            items={desks.items}
                        />
                    </div>

                    <DispatchForm
                        eyebrow={dispatch.eyebrow}
                        heading={dispatch.heading}
                        description={dispatch.description}
                        stats={dispatch.stats}
                        routingOptions={dispatch.routingOptions}
                        consentLabel={dispatch.consentLabel}
                        submitLabel={dispatch.submitLabel}
                    />

                    <div className="border-t border-border bg-muted/30">
                        <OfficeNetworkGrid
                            eyebrow={offices.eyebrow}
                            heading={offices.heading}
                            sublabel={offices.sublabel}
                            items={offices.items}
                        />
                    </div>
                </main>

                <SiteFooter />
            </div>
        </>
    );
}
