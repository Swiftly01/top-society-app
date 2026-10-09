import { AdvertSlider } from '@/components/home/advert-slider';
import type { Advert } from '@/types/content';

interface AdvertBannerProps {
    /** Empty hides the whole banner (and its spacing). */
    adverts: Advert[];
}

/**
 * The full-width advert slot between "Latest Reporting" and the category
 * cards. It is only a frame around the shared AdvertSlider: wide on
 * desktop, 16:9 on phones.
 */
export function AdvertBanner({ adverts }: AdvertBannerProps) {
    if (adverts.length === 0) return null;

    return (
        <section className="mx-auto max-w-7xl px-4 pb-10 sm:px-6 lg:px-8">
            <AdvertSlider
                adverts={adverts}
                ariaLabel="Sponsored banners"
                aspectClassName="aspect-video aspect-[40/7]"
                intervalMs={6000}
            />
        </section>
    );
}
