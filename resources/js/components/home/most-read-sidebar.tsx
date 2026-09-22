import { Link } from '@inertiajs/react';
import { TrendingUp } from 'lucide-react';
import { Button } from '@/components/ui/button';
import type { MostReadArticle, PromoCard } from '@/types/content';

interface MostReadSidebarProps {
    items: MostReadArticle[];
    promo: PromoCard;
}

export function MostReadSidebar({ items, promo }: MostReadSidebarProps) {
    return (
        <aside className="flex flex-col gap-6">
            <div>
                <h2 className="flex items-center gap-2 border-b border-border pb-3 font-serif text-lg font-bold text-foreground">
                    <TrendingUp className="size-4 text-red-600" />
                    Most Read
                </h2>
                <ol className="divide-y divide-border">
                    {items.map((item) => (
                        <li key={item.id}>
                            <Link href={item.href} className="group flex items-start gap-3 py-3">
                                <span className="font-serif text-xl leading-none font-bold text-muted-foreground/50">
                                    {item.rank}
                                </span>
                                <div className="flex flex-col gap-1">
                                    <span className="text-sm leading-snug font-semibold text-foreground group-hover:underline">
                                        {item.title}
                                    </span>
                                    <span className="text-[11px] text-muted-foreground">
                                        {item.category} · {item.views}
                                    </span>
                                </div>
                            </Link>
                        </li>
                    ))}
                </ol>
            </div>

            <div className="rounded-sm border border-border bg-muted/40 p-5">
                <span className="text-[10px] font-semibold tracking-widest text-red-600 uppercase">
                    {promo.label}
                </span>
                <h3 className="mt-2 font-serif text-base font-bold text-foreground">{promo.title}</h3>
                <p className="mt-1 text-sm text-muted-foreground">{promo.description}</p>
                <Button asChild variant="outline" size="sm" className="mt-4 border-red-600 text-red-600 hover:bg-red-600 hover:text-white">
                    <Link href={promo.href}>{promo.ctaLabel}</Link>
                </Button>
            </div>
        </aside>
    );
}
