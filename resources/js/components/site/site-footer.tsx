import { FormEvent, useState } from 'react';
import { Link, router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { useSiteNavigation } from '@/hooks/use-site-navigation';

export function SiteFooter() {
    const { footer } = useSiteNavigation();
    const [email, setEmail] = useState('');
    const [submitting, setSubmitting] = useState(false);

    function handleSubmit(e: FormEvent) {
        e.preventDefault();
        if (!email || submitting) return;

        setSubmitting(true);
        router.post(
            '/newsletter',
            { email },
            { preserveScroll: true, onFinish: () => setSubmitting(false), onSuccess: () => setEmail('') },
        );
    }

    return (
        <footer className="bg-neutral-950 text-neutral-400">
            <div className="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:px-6 lg:grid-cols-5 lg:px-8">
                <div className="lg:col-span-1">
                    <span className="font-serif text-xl font-bold text-white">{footer.brandName}</span>
                    <p className="mt-1 text-xs font-semibold tracking-widest text-red-500 uppercase">
                        {footer.editionLabel}
                    </p>
                    <p className="mt-3 text-sm">{footer.brandTagline}</p>
                    <p className="mt-4 text-xs text-neutral-500">Connect: {footer.socialHandle}</p>
                </div>

                {footer.linkGroups.map((group) => (
                    <div key={group.heading}>
                        <h3 className="text-xs font-semibold tracking-widest text-white uppercase">
                            {group.heading}
                        </h3>
                        <ul className="mt-3 flex flex-col gap-2">
                            {group.links.map((link) => (
                                <li key={link.href}>
                                    <Link href={link.href} className="text-sm hover:text-white">
                                        {link.label}
                                    </Link>
                                </li>
                            ))}
                        </ul>
                    </div>
                ))}

                <div>
                    <h3 className="text-xs font-semibold tracking-widest text-white uppercase">
                        {footer.newsletterHeading}
                    </h3>
                    <p className="mt-3 text-sm">Receive private society briefings and investigative dossiers in your inbox.</p>
                    <form onSubmit={handleSubmit} className="mt-3 flex flex-col gap-2">
                        <Input
                            type="email"
                            required
                            value={email}
                            onChange={(e) => setEmail(e.target.value)}
                            placeholder="Your email address"
                            className="border-neutral-700 bg-neutral-900 text-white placeholder:text-neutral-500"
                        />
                        <Button type="submit" disabled={submitting} className="bg-red-600 hover:bg-red-700">
                            {footer.newsletterCtaLabel}
                        </Button>
                    </form>
                </div>
            </div>

            <div className="border-t border-neutral-800">
                <div className="mx-auto flex max-w-7xl flex-col items-center justify-between gap-2 px-4 py-4 text-[11px] tracking-wide text-neutral-500 sm:flex-row sm:px-6 lg:px-8">
                    <span>{footer.copyright}</span>
                    <span className="uppercase">{footer.editions.join(' · ')}</span>
                </div>
            </div>
        </footer>
    );
}
