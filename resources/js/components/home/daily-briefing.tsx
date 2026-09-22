import { FormEvent, useState } from 'react';
import { router } from '@inertiajs/react';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import type { NewsletterSection } from '@/types/content';

interface DailyBriefingProps {
    newsletter: NewsletterSection;
}

export function DailyBriefing({ newsletter }: DailyBriefingProps) {
    const [email, setEmail] = useState('');
    const [submitting, setSubmitting] = useState(false);

    function handleSubmit(e: FormEvent) {
        e.preventDefault();
        if (!email || submitting) return;

        setSubmitting(true);
        router.post(
            '/newsletter',
            { email },
            {
                preserveScroll: true,
                onFinish: () => setSubmitting(false),
                onSuccess: () => setEmail(''),
            },
        );
    }

    return (
        <section className="mx-auto max-w-7xl px-4 pb-12 sm:px-6 lg:px-8">
            <div className="flex flex-col items-start gap-6 rounded-sm bg-neutral-950 p-8 sm:flex-row sm:items-center sm:justify-between sm:p-10">
                <div>
                    <h2 className="font-serif text-2xl font-bold text-white sm:text-3xl">{newsletter.title}</h2>
                    <p className="mt-2 max-w-md text-sm text-neutral-400">{newsletter.description}</p>
                </div>
                <form onSubmit={handleSubmit} className="flex w-full max-w-md gap-2 sm:w-auto">
                    <Input
                        type="email"
                        required
                        value={email}
                        onChange={(e) => setEmail(e.target.value)}
                        placeholder="Enter your email address"
                        className="border-neutral-700 bg-neutral-900 text-white placeholder:text-neutral-500"
                    />
                    <Button
                        type="submit"
                        disabled={submitting}
                        className="shrink-0 bg-red-600 hover:bg-red-700"
                    >
                        {newsletter.ctaLabel}
                    </Button>
                </form>
            </div>
        </section>
    );
}
