import { Head, Link } from '@inertiajs/react';
import { Download, Printer } from 'lucide-react';
import { LegalBlockRenderer } from '@/components/legal/legal-block-renderer';
import { SiteFooter } from '@/components/site/site-footer';
import { SiteHeader } from '@/components/site/site-header';
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from '@/components/ui/breadcrumb';
import { Button } from '@/components/ui/button';
import type { LegalDocumentPageProps } from '@/types/legal';

export default function LegalShow(props: LegalDocumentPageProps) {
    const {
        breadcrumb,
        eyebrow,
        title,
        description,
        meta,
        tocHeading,
        toc,
        sidebarNotice,
        downloadHref,
        downloadLabel,
        printLabel,
        sections,
        acknowledgement,
    } = props;

    return (
        <>
            <Head title={title} />

            <div className="min-h-screen bg-background text-foreground">
                <SiteHeader activeNav="Home" />

                <main>
                    <section className="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
                        <div className="flex flex-col gap-3 border-b border-border pb-6 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <Breadcrumb>
                                    <BreadcrumbList>
                                        {breadcrumb.map((crumb, i) => (
                                            <div key={crumb.href} className="flex items-center gap-1.5">
                                                <BreadcrumbItem>
                                                    {i === breadcrumb.length - 1 ? (
                                                        <BreadcrumbPage className="text-[11px] tracking-widest uppercase">
                                                            {crumb.label}
                                                        </BreadcrumbPage>
                                                    ) : (
                                                        <BreadcrumbLink asChild>
                                                            <Link href={crumb.href} className="text-[11px] tracking-widest uppercase">
                                                                {crumb.label}
                                                            </Link>
                                                        </BreadcrumbLink>
                                                    )}
                                                </BreadcrumbItem>
                                                {i < breadcrumb.length - 1 && <BreadcrumbSeparator />}
                                            </div>
                                        ))}
                                    </BreadcrumbList>
                                </Breadcrumb>

                                <span className="mt-3 block text-[11px] font-semibold tracking-widest text-red-600 uppercase">
                                    {eyebrow}
                                </span>
                                <h1 className="mt-1 font-serif text-3xl leading-tight font-bold sm:text-4xl">{title}</h1>
                                {description && <p className="mt-2 max-w-2xl text-sm text-muted-foreground">{description}</p>}

                                <div className="mt-4 flex flex-wrap gap-x-6 gap-y-1 text-xs text-muted-foreground">
                                    {meta.map((item) => (
                                        <span key={item.label}>
                                            <span className="font-semibold text-foreground">{item.label}:</span> {item.value}
                                        </span>
                                    ))}
                                </div>
                            </div>

                            {(downloadHref || printLabel) && (
                                <div className="flex shrink-0 gap-2">
                                    {printLabel && (
                                        <Button variant="outline" size="sm" onClick={() => window.print()}>
                                            <Printer className="size-4" />
                                            {printLabel}
                                        </Button>
                                    )}
                                    {downloadHref && (
                                        <Button asChild variant="outline" size="sm">
                                            <a href={downloadHref}>
                                                <Download className="size-4" />
                                                {downloadLabel ?? 'Download'}
                                            </a>
                                        </Button>
                                    )}
                                </div>
                            )}
                        </div>

                        <div className="mt-8 grid gap-10 lg:grid-cols-[260px_1fr]">
                            {/* TOC sidebar */}
                            <aside className="flex flex-col gap-6 lg:sticky lg:top-8 lg:h-fit">
                                <div>
                                    <p className="mb-3 text-xs font-semibold tracking-widest text-muted-foreground uppercase">
                                        {tocHeading}
                                    </p>
                                    <ul className="flex flex-col gap-1.5">
                                        {toc.map((item) => (
                                            <li key={item.id}>
                                                <a href={`#${item.id}`} className="text-sm text-foreground hover:text-red-600 hover:underline">
                                                    {item.label}
                                                </a>
                                            </li>
                                        ))}
                                    </ul>
                                </div>

                                {sidebarNotice && (
                                    <div className="rounded-sm bg-neutral-950 p-4 text-white">
                                        <p className="text-sm font-semibold">{sidebarNotice.title}</p>
                                        <p className="mt-1 text-xs text-neutral-400">{sidebarNotice.body}</p>
                                    </div>
                                )}
                            </aside>

                            {/* Sections */}
                            <div className="flex flex-col gap-10">
                                {sections.map((section) => (
                                    <div key={section.id} id={section.id} className="scroll-mt-24">
                                        <h2 className="flex items-baseline gap-2 font-serif text-2xl font-bold">
                                            {section.number && <span className="text-red-600">{section.number}.</span>}
                                            {section.heading}
                                        </h2>
                                        <div className="mt-3 flex flex-col gap-4">
                                            {section.blocks.map((block, i) => (
                                                <LegalBlockRenderer key={i} block={block} />
                                            ))}
                                        </div>
                                    </div>
                                ))}

                                {acknowledgement && (
                                    <div className="flex flex-col items-start justify-between gap-4 border-t border-border pt-6 sm:flex-row sm:items-center">
                                        <p className="text-sm text-muted-foreground">{acknowledgement.label}</p>
                                        <Button className="shrink-0 bg-red-600 hover:bg-red-700">
                                            {acknowledgement.ctaLabel}
                                        </Button>
                                    </div>
                                )}
                            </div>
                        </div>
                    </section>
                </main>

                <SiteFooter />
            </div>
        </>
    );
}
