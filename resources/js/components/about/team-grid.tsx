import { Link } from '@inertiajs/react';
import { ArrowRight } from 'lucide-react';
import { ArticleMedia } from '@/components/site/article-media';
import type { TeamMember } from '@/types/about';

interface TeamGridProps {
    eyebrow: string;
    heading: string;
    sublabel: string;
    members: TeamMember[];
}

export function TeamGrid({ eyebrow, heading, sublabel, members }: TeamGridProps) {
    return (
        <section className="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <div className="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <span className="text-[11px] font-semibold tracking-widest text-red-600 uppercase">{eyebrow}</span>
                    <h2 className="mt-1 font-serif text-3xl font-bold">{heading}</h2>
                </div>
                <span className="text-[11px] tracking-wide text-muted-foreground uppercase">{sublabel}</span>
            </div>

            <div className="mt-8 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                {members.map((member) => {
                    const content = (
                        <>
                            <div className="relative">
                                <ArticleMedia src={member.photo} alt={member.name} className="aspect-[4/5] w-full" />
                                <span className="absolute bottom-3 left-3 rounded-sm bg-black/80 px-2 py-1 text-[10px] font-semibold tracking-widest text-white uppercase">
                                    {member.role}
                                </span>
                            </div>
                            <h3 className="mt-3 font-serif text-lg font-bold group-hover:underline">{member.name}</h3>
                            <p className="text-xs font-semibold tracking-wide text-red-600 uppercase">{member.role}</p>
                            <p className="mt-1 line-clamp-3 text-sm text-muted-foreground">{member.bio}</p>
                            {member.href && (
                                <span className="mt-2 inline-flex items-center gap-1 text-xs font-medium text-muted-foreground">
                                    Profile <ArrowRight className="size-3" />
                                </span>
                            )}
                        </>
                    );

                    return member.href ? (
                        <Link key={member.id} href={member.href} className="group flex flex-col">
                            {content}
                        </Link>
                    ) : (
                        <div key={member.id} className="group flex flex-col">
                            {content}
                        </div>
                    );
                })}
            </div>
        </section>
    );
}
