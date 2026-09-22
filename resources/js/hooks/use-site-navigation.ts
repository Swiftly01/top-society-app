import { usePage } from '@inertiajs/react';
import type { SiteShared } from '@/types/content';

/**
 * Reads the `site` object shared by `HandleInertiaRequests` on every
 * request (see `app/Http/Middleware/HandleInertiaRequests.php`). Header,
 * footer, and nav components pull from this instead of receiving the same
 * data threaded down as props on every single page.
 */
export function useSiteNavigation(): SiteShared {
    return usePage().props.site;
}
