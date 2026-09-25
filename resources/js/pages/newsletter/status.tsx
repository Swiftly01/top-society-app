import { Head, Link } from '@inertiajs/react';
import { CheckCircle2, MailCheck, XCircle } from 'lucide-react';
import { SectionHeader } from '@/components/site/section-header';
import { SiteFooter } from '@/components/site/site-footer';

interface StatusPageProps {
    status: 'verified' | 'unsubscribed' | 'invalid';
    newsletterName?: string | null;
}

const copy = {
    verified: {
        icon: MailCheck,
        title: "You're subscribed!",
        body: (name?: string | null) => `You'll now receive ${name ?? 'this newsletter'} straight to your inbox.`,
    },
    unsubscribed: {
        icon: CheckCircle2,
        title: "You've been unsubscribed",
        body: (name?: string | null) => `You won't receive any more emails from ${name ?? 'this newsletter'}.`,
    },
    invalid: {
        icon: XCircle,
        title: 'Link no longer valid',
        body: () => 'This link has already been used or has expired.',
    },
} as const;

export default function NewsletterStatus({ status, newsletterName }: StatusPageProps) {
    const { icon: Icon, title, body } = copy[status];

    return (
        <>
            <Head title={title} />

            <div className="min-h-screen bg-background text-foreground">
                <SectionHeader showSubscribe={false} />

                <div className="mx-auto flex max-w-lg flex-col items-center px-4 py-24 text-center sm:px-6 lg:px-8">
                    <Icon className={status === 'invalid' ? 'size-12 text-muted-foreground' : 'size-12 text-red-600'} />
                    <h1 className="mt-4 font-serif text-2xl font-bold">{title}</h1>
                    <p className="mt-2 text-sm text-muted-foreground">{body(newsletterName)}</p>
                    <Link href="/newsletter" className="mt-6 text-sm font-medium text-red-600 underline underline-offset-2">
                        Browse our newsletters →
                    </Link>
                </div>

                <SiteFooter />
            </div>
        </>
    );
}