import { useState } from "react";
import { Link } from "@inertiajs/react";
import { Menu, Search } from "lucide-react";
import { Button } from "@/components/ui/button";
import {
    Sheet,
    SheetContent,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from "@/components/ui/sheet";
import { useSiteNavigation } from "@/hooks/use-site-navigation";
import { cn } from "@/lib/utils";
import { SearchModal } from "@/components/search/search-modal";

interface SiteHeaderProps {
    activeNav: string;
    nav?: "primary" | "section";
}

export function SiteHeader({ activeNav, nav = "primary" }: SiteHeaderProps) {
    const { editionDate, editionLabel, navItems, sectionNavItems } =
        useSiteNavigation();
    const items = nav === "section" ? sectionNavItems : navItems;
    const [mobileOpen, setMobileOpen] = useState(false);
    const [searchOpen, setSearchOpen] = useState(false);

    return (
        <header className="border-b border-border bg-background">
            {/* Utility bar */}
            <div className="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-2 text-[11px] tracking-wide text-muted-foreground sm:px-6 lg:px-8">
                <span className="uppercase">{editionDate}</span>
                <span className="hidden font-medium text-red-600 uppercase sm:inline">
                    Edition: {editionLabel}
                </span>
            </div>

            <div className="mx-auto max-w-7xl border-t border-border px-4 py-4 sm:px-6 lg:px-8">
                <div className="grid grid-cols-[auto_1fr_auto] items-center gap-3">
                    {/* Mobile menu trigger */}
                    <div className="flex items-center lg:hidden">
                        <Sheet open={mobileOpen} onOpenChange={setMobileOpen}>
                            <SheetTrigger asChild>
                                <Button
                                    variant="ghost"
                                    size="icon"
                                    aria-label="Open menu"
                                >
                                    <Menu className="size-5" />
                                </Button>
                            </SheetTrigger>
                            <SheetContent side="left" className="w-72">
                                <SheetHeader>
                                    <SheetTitle asChild>
                                        <Link href="/" onClick={() => setMobileOpen(false)}>
                                            <img
                                                src="/images/top-society-logo.png"
                                                alt="Top Society"
                                                className="h-9 w-auto"
                                            />
                                        </Link>
                                    </SheetTitle>
                                </SheetHeader>
                                <nav className="flex flex-col gap-1 px-4 pb-6">
                                    {items.map((item) => (
                                        <Link
                                            key={item.href}
                                            href={item.href}
                                            onClick={() => setMobileOpen(false)}
                                            className={cn(
                                                "rounded-md px-2 py-2 text-sm font-medium uppercase tracking-wide",
                                                item.label === activeNav
                                                    ? "bg-red-50 text-red-600 dark:bg-red-950/40"
                                                    : "text-foreground hover:bg-accent",
                                            )}
                                        >
                                            {item.label}
                                        </Link>
                                    ))}
                                </nav>
                            </SheetContent>
                        </Sheet>
                    </div>

                    {/* Logo */}
                    <div className="col-start-2 text-center lg:col-start-1 lg:text-left">
                        <Link href="/" className="inline-block">
                            <img
                                src="/images/top-society-logo.png"
                                alt="Top Society"
                                className="h-12 w-auto sm:h-14"
                            />
                        </Link>
                        <p className="hidden text-[11px] tracking-[0.2em] text-muted-foreground uppercase lg:block">
                            Nigeria &amp; Global Journal of Authority
                        </p>
                    </div>

                    {/* Actions */}
                    <div className="col-start-3 flex items-center justify-end gap-1 lg:col-start-3">
                        <Button
                            variant="ghost"
                            size="icon"
                            aria-label="Search"
                            onClick={() => setSearchOpen(true)}
                        >
                            <Search className="size-4" />
                        </Button>
                        <Button
                            className="hidden bg-red-600 hover:bg-red-700 sm:inline-flex"
                            size="sm"
                        >
                            Subscribe
                        </Button>
                    </div>
                </div>

                {/* Mobile tagline */}
                <p className="mt-1 text-center text-[10px] tracking-[0.2em] text-muted-foreground uppercase lg:hidden">
                    Nigeria &amp; Global Journal of Authority
                </p>
            </div>

            {/* Primary nav — desktop */}
            <nav className="hidden border-t border-border lg:block">
                <div className="mx-auto flex max-w-7xl items-center gap-6 overflow-x-auto px-4 sm:px-6 lg:px-8">
                    {items.map((item) => (
                        <Link
                            key={item.href}
                            href={item.href}
                            className={cn(
                                "border-b-2 py-3 text-xs font-semibold tracking-wide whitespace-nowrap uppercase transition-colors",
                                item.label === activeNav
                                    ? "border-red-600 text-red-600"
                                    : "border-transparent text-foreground hover:text-red-600",
                            )}
                        >
                            {item.label}
                        </Link>
                    ))}
                </div>
            </nav>
            <SearchModal open={searchOpen} onOpenChange={setSearchOpen} />
        </header>
    );
}
