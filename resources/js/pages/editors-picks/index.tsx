// import { Head, Link } from '@inertiajs/react';
// import { ArrowUpRight, FileText } from 'lucide-react';
// import { ArticleMedia } from '@/components/site/article-media';
// import { SectionHeader } from '@/components/site/section-header';
// import { SiteFooter } from '@/components/site/site-footer';
// import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
// import { Separator } from '@/components/ui/separator';
// import type { EditorsPicksPageProps } from '@/types/editors-picks';

// export default function EditorsPicksIndex(props: EditorsPicksPageProps) {
//     const { activeNav, title, description, curator, featured, secondary } = props;

//     return (
//         <>
//             <Head title={title} />

//             <div className="min-h-screen bg-background text-foreground">
//                 <SectionHeader activeNav={activeNav} />

//                 <main className="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
//                     <div className="flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">
//                         <div className="max-w-2xl">
//                             <h1 className="font-serif text-5xl font-bold">{title}</h1>
//                             <p className="mt-3 text-base text-muted-foreground">{description}</p>
//                         </div>

//                         <div className="flex shrink-0 items-center gap-3 sm:border-l sm:border-border sm:pl-6">
//                             <div className="text-right text-xs text-muted-foreground">
//                                 <p className="font-semibold tracking-widest uppercase">Curated By</p>
//                             </div>
//                             <Avatar className="size-10">
//                                 <AvatarImage src={curator.avatar ?? undefined} alt={curator.name} />
//                                 <AvatarFallback>
//                                     {curator.name
//                                         .split(' ')
//                                         .map((p) => p[0])
//                                         .join('')}
//                                 </AvatarFallback>
//                             </Avatar>
//                             <div>
//                                 <p className="text-sm font-semibold">{curator.name}</p>
//                                 <p className="text-xs text-muted-foreground">{curator.title}</p>
//                             </div>
//                         </div>
//                     </div>

//                     <div className="mt-10 grid gap-6 lg:grid-cols-[1.4fr_1fr]">
//                         {/* Featured pick */}
//                         <Link href={featured.href} className="group grid overflow-hidden rounded-sm border border-border sm:grid-cols-2">
//                             <div className="relative">
//                                 <ArticleMedia src={featured.image} alt={featured.title} className="h-full min-h-64 w-full" />
//                                 <span className="absolute top-3 left-3 rounded-sm bg-red-600 px-2 py-1 text-[11px] font-semibold tracking-wide text-white uppercase">
//                                     {featured.badge}
//                                 </span>
//                             </div>
//                             <div className="flex flex-col justify-between p-6">
//                                 <h2 className="font-serif text-3xl leading-tight font-bold group-hover:underline">
//                                     {featured.title}
//                                 </h2>
//                                 <div>
//                                     <p className="mb-4 text-sm text-muted-foreground">{featured.excerpt}</p>
//                                     <Separator className="mb-3" />
//                                     <span className="flex items-center gap-1.5 text-xs font-medium text-muted-foreground">
//                                         <FileText className="size-3.5" />
//                                         By {featured.author}
//                                     </span>
//                                 </div>
//                             </div>
//                         </Link>

//                         {/* Secondary picks */}
//                         <div className="flex flex-col gap-6">
//                             {secondary.map((pick) => (
//                                 <Link
//                                     key={pick.id}
//                                     href={pick.href}
//                                     className="group flex flex-1 flex-col rounded-sm border border-border p-6"
//                                 >
//                                     <div className="mb-3 flex items-center justify-between">
//                                         <span className="text-xs font-semibold tracking-widest text-muted-foreground uppercase">
//                                             {pick.badge}
//                                         </span>
//                                         <ArrowUpRight className="size-4 text-muted-foreground transition-transform group-hover:-translate-y-0.5 group-hover:translate-x-0.5 group-hover:text-red-600" />
//                                     </div>
//                                     <h3 className="font-serif text-xl leading-snug font-bold group-hover:underline">
//                                         {pick.title}
//                                     </h3>
//                                     <p className="mt-2 text-sm text-muted-foreground">{pick.excerpt}</p>
//                                     <span className="mt-4 text-xs font-medium text-muted-foreground">By {pick.author}</span>
//                                 </Link>
//                             ))}
//                         </div>
//                     </div>
//                 </main>

//                 <SiteFooter />
//             </div>
//         </>
//     );
// }


import { Head, Link } from '@inertiajs/react';
import { ArrowUpRight, FileText } from 'lucide-react';
import { ArticleMedia } from '@/components/site/article-media';
import { SectionHeader } from '@/components/site/section-header';
import { SiteFooter } from '@/components/site/site-footer';
import { Avatar, AvatarFallback, AvatarImage } from '@/components/ui/avatar';
import { Separator } from '@/components/ui/separator';
import type { EditorsPicksPageProps } from '@/types/editors-picks';

export default function EditorsPicksIndex(props: EditorsPicksPageProps) {
    const { activeNav, title, description, curator, featured, secondary } = props;

    return (
        <>
            <Head title={title} />

            <div className="min-h-screen bg-background text-foreground">
                <SectionHeader activeNav={activeNav} />

                <main className="mx-auto max-w-6xl px-4 py-10 sm:px-6 lg:px-8">
                    <div className="flex flex-col gap-6 sm:flex-row sm:items-start sm:justify-between">
                        <div className="max-w-2xl">
                            <h1 className="font-serif text-5xl font-bold">{title}</h1>
                            <p className="mt-3 text-base text-muted-foreground">{description}</p>
                        </div>

                        <div className="flex shrink-0 items-center gap-3 sm:border-l sm:border-border sm:pl-6">
                            <div className="text-right text-xs text-muted-foreground">
                                <p className="font-semibold tracking-widest uppercase">Curated By</p>
                            </div>
                            <Avatar className="size-10">
                                <AvatarImage src={curator.avatar ?? undefined} alt={curator.name} />
                                <AvatarFallback>
                                    {curator.name
                                        .split(' ')
                                        .map((p) => p[0])
                                        .join('')}
                                </AvatarFallback>
                            </Avatar>
                            <div>
                                <p className="text-sm font-semibold">{curator.name}</p>
                                <p className="text-xs text-muted-foreground">{curator.title}</p>
                            </div>
                        </div>
                    </div>

                    <div className="mt-10 grid gap-6 lg:grid-cols-[1.4fr_1fr]">
                        {/* Featured pick */}
                        {featured ? (
                            <Link href={featured.href} className="group grid overflow-hidden rounded-sm border border-border sm:grid-cols-2">
                                <div className="relative">
                                    <ArticleMedia src={featured.image} alt={featured.title} className="h-full min-h-64 w-full" />
                                    <span className="absolute top-3 left-3 rounded-sm bg-red-600 px-2 py-1 text-[11px] font-semibold tracking-wide text-white uppercase">
                                        {featured.badge}
                                    </span>
                                </div>
                                <div className="flex flex-col justify-between p-6">
                                    <h2 className="font-serif text-3xl leading-tight font-bold group-hover:underline">
                                        {featured.title}
                                    </h2>
                                    <div>
                                        <p className="mb-4 text-sm text-muted-foreground">{featured.excerpt}</p>
                                        <Separator className="mb-3" />
                                        <span className="flex items-center gap-1.5 text-xs font-medium text-muted-foreground">
                                            <FileText className="size-3.5" />
                                            By {featured.author}
                                        </span>
                                    </div>
                                </div>
                            </Link>
                        ) : (
                            <div className="flex items-center justify-center rounded-sm border border-dashed border-border p-10 text-sm text-muted-foreground">
                                No stories are marked as featured yet.
                            </div>
                        )}

                        {/* Secondary picks */}
                        <div className="flex flex-col gap-6">
                            {secondary.map((pick) => (
                                <Link
                                    key={pick.id}
                                    href={pick.href}
                                    className="group flex flex-1 flex-col rounded-sm border border-border p-6"
                                >
                                    <div className="mb-3 flex items-center justify-between">
                                        <span className="text-xs font-semibold tracking-widest text-muted-foreground uppercase">
                                            {pick.badge}
                                        </span>
                                        <ArrowUpRight className="size-4 text-muted-foreground transition-transform group-hover:-translate-y-0.5 group-hover:translate-x-0.5 group-hover:text-red-600" />
                                    </div>
                                    <h3 className="font-serif text-xl leading-snug font-bold group-hover:underline">
                                        {pick.title}
                                    </h3>
                                    <p className="mt-2 text-sm text-muted-foreground">{pick.excerpt}</p>
                                    <span className="mt-4 text-xs font-medium text-muted-foreground">By {pick.author}</span>
                                </Link>
                            ))}
                        </div>
                    </div>
                </main>

                <SiteFooter />
            </div>
        </>
    );
}
