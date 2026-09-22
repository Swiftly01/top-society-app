// import { Link } from '@inertiajs/react';
// import { ArrowRight } from 'lucide-react';
// import { ArticleMedia } from '@/components/site/article-media';
// import type { PartnershipSection } from '@/types/content';

// interface PartnershipDossierProps {
//     partnership: PartnershipSection;
// }

// export function PartnershipDossier({ partnership }: PartnershipDossierProps) {
//     const { tag, title, description, disclosureLabel, featured, features } = partnership;

//     return (
//         <section className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
//             <div className="border-l-4 border-red-600 bg-muted/40 p-5 sm:p-8">
//                 <div className="mb-6 flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
//                     <div>
//                         <span className="text-[11px] font-semibold tracking-widest text-red-600 uppercase">
//                             {tag}
//                         </span>
//                         <h2 className="mt-1 font-serif text-2xl font-bold text-foreground">{title}</h2>
//                         <p className="mt-1 max-w-2xl text-sm text-muted-foreground italic">{description}</p>
//                     </div>
//                     <span className="shrink-0 text-[11px] font-medium tracking-wide text-muted-foreground uppercase">
//                         {disclosureLabel}
//                     </span>
//                 </div>

//                 <div className="grid gap-6 lg:grid-cols-2">
//                     {/* Featured partnership story */}
//                     <Link href={featured.href} className="group flex flex-col">
//                         <div className="relative overflow-hidden rounded-sm">
//                             <ArticleMedia src={featured.image} alt={featured.title} className="aspect-[16/10] w-full" />
//                             {featured.collaborationLabel && (
//                                 <span className="absolute top-3 left-3 rounded-sm bg-black/80 px-2 py-1 text-[10px] font-semibold tracking-wide text-white uppercase">
//                                     {featured.collaborationLabel}
//                                 </span>
//                             )}
//                         </div>
//                         <span className="mt-4 text-[11px] font-semibold tracking-wide text-red-600 uppercase">
//                             {featured.category}
//                         </span>
//                         <h3 className="mt-1 font-serif text-xl font-bold text-foreground group-hover:underline">
//                             {featured.title}
//                         </h3>
//                         {featured.excerpt && (
//                             <p className="mt-2 line-clamp-2 text-sm text-muted-foreground">{featured.excerpt}</p>
//                         )}
//                         <div className="mt-3 flex items-center justify-between border-t border-border pt-3 text-[11px] text-muted-foreground">
//                             <span>
//                                 {featured.sponsorLabel} {featured.readTime && <>· {featured.readTime}</>}
//                             </span>
//                             <span className="inline-flex items-center gap-1 font-medium text-red-600">
//                                 Read Feature <ArrowRight className="size-3" />
//                             </span>
//                         </div>
//                     </Link>

//                     {/* Secondary partnership stories */}
//                     <div className="flex flex-col divide-y divide-border">
//                         {features.map((item) => (
//                             <Link
//                                 key={item.id}
//                                 href={item.href}
//                                 className="group flex flex-col gap-1.5 py-5 first:pt-0"
//                             >
//                                 <div className="flex items-center justify-between">
//                                     <span className="text-[10px] font-semibold tracking-wide text-muted-foreground uppercase">
//                                         {item.sponsorLabel}
//                                     </span>
//                                     {item.readTime && (
//                                         <span className="text-[10px] text-muted-foreground">{item.readTime}</span>
//                                     )}
//                                 </div>
//                                 <h4 className="font-serif text-base leading-snug font-bold text-foreground group-hover:underline">
//                                     {item.title}
//                                 </h4>
//                                 {item.excerpt && (
//                                     <p className="line-clamp-2 text-sm text-muted-foreground">{item.excerpt}</p>
//                                 )}
//                                 <div className="mt-1 flex items-center justify-between text-[11px]">
//                                     <span className="text-muted-foreground">{item.category}</span>
//                                     <span className="inline-flex items-center gap-1 font-medium text-red-600">
//                                         Read Feature <ArrowRight className="size-3" />
//                                     </span>
//                                 </div>
//                             </Link>
//                         ))}
//                     </div>
//                 </div>
//             </div>
//         </section>
//     );
// }


import { Link } from '@inertiajs/react';
import { ArrowRight, PlayCircle } from 'lucide-react';
import { ArticleMedia } from '@/components/site/article-media';
import type { PartnershipSection } from '@/types/content';

interface PartnershipDossierProps {
    partnership: PartnershipSection;
}

export function PartnershipDossier({ partnership }: PartnershipDossierProps) {
    const { tag, title, description, disclosureLabel, featured, features } = partnership;

    return (
        <section className="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div className="border-l-4 border-red-600 bg-muted/40 p-5 sm:p-8">
                <div className="mb-6 flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                    <div>
                        <span className="text-[11px] font-semibold tracking-widest text-red-600 uppercase">
                            {tag}
                        </span>
                        <h2 className="mt-1 font-serif text-2xl font-bold text-foreground">{title}</h2>
                        <p className="mt-1 max-w-2xl text-sm text-muted-foreground italic">{description}</p>
                    </div>
                    <span className="shrink-0 text-[11px] font-medium tracking-wide text-muted-foreground uppercase">
                        {disclosureLabel}
                    </span>
                </div>

                <div className="grid gap-6 lg:grid-cols-2">
                    {/* Featured partnership story */}
                    <Link href={featured.href} className="group flex flex-col">
                        <div className="relative overflow-hidden rounded-sm">
                            <ArticleMedia src={featured.image} alt={featured.title} className="aspect-[16/10] w-full" />
                            {featured.isVideo && (
                                <span className="absolute inset-0 flex items-center justify-center bg-black/20">
                                    <PlayCircle className="size-14 text-white drop-shadow-lg" strokeWidth={1.5} />
                                </span>
                            )}
                            {featured.collaborationLabel && (
                                <span className="absolute top-3 left-3 rounded-sm bg-black/80 px-2 py-1 text-[10px] font-semibold tracking-wide text-white uppercase">
                                    {featured.collaborationLabel}
                                </span>
                            )}
                        </div>
                        <span className="mt-4 text-[11px] font-semibold tracking-wide text-red-600 uppercase">
                            {featured.category}
                        </span>
                        <h3 className="mt-1 font-serif text-xl font-bold text-foreground group-hover:underline">
                            {featured.title}
                        </h3>
                        {featured.excerpt && (
                            <p className="mt-2 line-clamp-2 text-sm text-muted-foreground">{featured.excerpt}</p>
                        )}
                        <div className="mt-3 flex items-center justify-between border-t border-border pt-3 text-[11px] text-muted-foreground">
                            <span>
                                {featured.sponsorLabel} {featured.readTime && <>· {featured.readTime}</>}
                            </span>
                            <span className="inline-flex items-center gap-1 font-medium text-red-600">
                                Read Feature <ArrowRight className="size-3" />
                            </span>
                        </div>
                    </Link>

                    {/* Secondary partnership stories */}
                    <div className="flex flex-col divide-y divide-border">
                        {features.map((item) => (
                            <Link
                                key={item.id}
                                href={item.href}
                                className="group flex flex-col gap-1.5 py-5 first:pt-0"
                            >
                                <div className="flex items-center justify-between">
                                    <span className="text-[10px] font-semibold tracking-wide text-muted-foreground uppercase">
                                        {item.sponsorLabel}
                                    </span>
                                    {item.readTime && (
                                        <span className="text-[10px] text-muted-foreground">{item.readTime}</span>
                                    )}
                                </div>
                                <h4 className="font-serif text-base leading-snug font-bold text-foreground group-hover:underline">
                                    {item.title}
                                </h4>
                                {item.excerpt && (
                                    <p className="line-clamp-2 text-sm text-muted-foreground">{item.excerpt}</p>
                                )}
                                <div className="mt-1 flex items-center justify-between text-[11px]">
                                    <span className="text-muted-foreground">{item.category}</span>
                                    <span className="inline-flex items-center gap-1 font-medium text-red-600">
                                        Read Feature <ArrowRight className="size-3" />
                                    </span>
                                </div>
                            </Link>
                        ))}
                    </div>
                </div>
            </div>
        </section>
    );
}

