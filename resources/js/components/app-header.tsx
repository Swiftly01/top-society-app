import { Link, usePage } from "@inertiajs/react";
import { LayoutGrid, Menu, Search } from "lucide-react";
import AppLogo from "@/components/app-logo";
import AppLogoIcon from "@/components/app-logo-icon";
import { Breadcrumbs } from "@/components/breadcrumbs";
import { Avatar, AvatarFallback, AvatarImage } from "@/components/ui/avatar";
import { Button } from "@/components/ui/button";
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuTrigger,
} from "@/components/ui/dropdown-menu";
import {
    NavigationMenu,
    NavigationMenuItem,
    NavigationMenuList,
    navigationMenuTriggerStyle,
} from "@/components/ui/navigation-menu";
import {
    Sheet,
    SheetContent,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from "@/components/ui/sheet";
import { UserMenuContent } from "@/components/user-menu-content";
import { useCurrentUrl } from "@/hooks/use-current-url";
import { useInitials } from "@/hooks/use-initials";
import { cn } from "@/lib/utils";
import { dashboard } from "@/routes";
import type { BreadcrumbItem, NavItem } from "@/types";

type Props = {
    breadcrumbs?: BreadcrumbItem[];
};

const mainNavItems: NavItem[] = [
    {
        title: "Dashboard",
        href: dashboard(),
        icon: LayoutGrid,
    },
];

function AnnouncementMarquee() {
    const message = "Celebrating 12 Years of Excellence in Journalism";

    return (
        <div className="relative w-full overflow-hidden bg-black h-14">
            <span className="text-xs font-semibold tracking-widest text-white uppercase -translate-y-1/2 marquee-track top-1/2 motion-reduce:hidden">
                {message}
            </span>
            <span
                className="text-xs font-semibold tracking-widest text-white uppercase -translate-y-1/2 marquee-track marquee-track-delayed top-1/2 motion-reduce:hidden"
                aria-hidden="true"
            >
                {message}
            </span>
            <span className="absolute hidden text-xs font-semibold tracking-widest text-red-600 uppercase -translate-x-1/2 -translate-y-1/2 top-1/2 left-1/2 motion-reduce:block">
                {message}
            </span>
        </div>
    );
}

export function AppHeader({ breadcrumbs = [] }: Props) {
    const page = usePage();
    const { auth } = page.props;
    const getInitials = useInitials();
    const { isCurrentUrl, whenCurrentUrl } = useCurrentUrl();
    const message = "Celebrating 12 Years of Excellence in Journalism";

    return (
        <>
            <div className="border-b border-sidebar-border/80">
                <AnnouncementMarquee/>
            </div>
        </>
    );
}
