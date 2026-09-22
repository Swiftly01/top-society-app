import { PlaceholderPattern } from '@/components/ui/placeholder-pattern';
import { cn } from '@/lib/utils';

interface ArticleMediaProps {
    src?: string | null;
    alt: string;
    className?: string;
}

/**
 * Renders an article's cover image, falling back to the app's placeholder
 * pattern whenever `src` is empty — e.g. while an editor hasn't uploaded
 * artwork yet, or a feed article arrives without media.
 */
export function ArticleMedia({ src, alt, className }: ArticleMediaProps) {
    if (!src) {
        return (
            <div className={cn('relative overflow-hidden bg-muted', className)}>
                <PlaceholderPattern className="absolute inset-0 size-full stroke-neutral-300 dark:stroke-neutral-700" />
            </div>
        );
    }

    return (
        <div className={cn('overflow-hidden bg-muted', className)}>
            <img
                src={src}
                alt={alt}
                loading="lazy"
                className="size-full object-cover transition-transform duration-500 group-hover:scale-105"
            />
        </div>
    );
}
