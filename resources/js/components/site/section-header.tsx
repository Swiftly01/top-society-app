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

interface SectionHeaderProps {
    activeNav?: string;
    showSubscribe?: boolean;
}

/**
 * Compact header used on inner pages (article, search, editor's picks,
 * newsletter) — a section nav row plus search/subscribe, without the
 * homepage's masthead/edition bar. Shares the mobile sheet-menu pattern
 * with `SiteHeader` so both feel like one product.
 */
export function SectionHeader({
    activeNav,
    showSubscribe = true,
}: SectionHeaderProps) {
    const { sectionNavItems } = useSiteNavigation();
    const [mobileOpen, setMobileOpen] = useState(false);
    const [searchOpen, setSearchOpen] = useState(false);

    return (
        <header className="border-b border-border bg-background">
            <div className="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                <div className="flex items-center gap-2 lg:hidden">
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
                                {sectionNavItems.map((item) => (
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
                    <Link href="/">
                        <img
                            src="/images/top-society-logo.png"
                            alt="Top Society"
                            className="h-8 w-auto"
                        />
                    </Link>
                </div>

                <Link href="/" className="hidden lg:block">
                    <img
                        src="/images/top-society-logo.png"
                        alt="Top Society"
                        className="h-9 w-auto"
                    />
                </Link>

                <nav className="hidden items-center gap-6 lg:flex">
                    {sectionNavItems.map((item) => (
                        <Link
                            key={item.href}
                            href={item.href}
                            className={cn(
                                "border-b-2 py-1 text-xs font-semibold tracking-wide whitespace-nowrap uppercase transition-colors",
                                item.label === activeNav
                                    ? "border-red-600 text-red-600"
                                    : "border-transparent text-foreground hover:text-red-600",
                            )}
                        >
                            {item.label}
                        </Link>
                    ))}
                </nav>

                <div className="flex items-center gap-1">
                    <Button
                        variant="ghost"
                        size="icon"
                        aria-label="Search"
                        onClick={() => setSearchOpen(true)}
                    >
                        <Search className="size-4" />
                    </Button>
                    {showSubscribe && (
                        <Button
                            className="bg-red-600 hover:bg-red-700"
                            size="sm"
                        >
                            Subscribe
                        </Button>
                    )}
                </div>
            </div>
            <SearchModal open={searchOpen} onOpenChange={setSearchOpen} />
        </header>
    );
}
