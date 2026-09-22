import { FormEvent, useState } from 'react';
import { Link, router } from '@inertiajs/react';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import type { NewsletterPlan } from '@/types/newsletter';

interface NewsletterPlanCardProps {
    plan: NewsletterPlan;
}

export function NewsletterPlanCard({ plan }: NewsletterPlanCardProps) {
    const [email, setEmail] = useState('');
    const [submitting, setSubmitting] = useState(false);

    function handleSubmit(e: FormEvent) {
        e.preventDefault();
        if (!email || submitting) return;
        setSubmitting(true);
        router.post(
            '/newsletter',
            { email, plan: plan.id },
            { preserveScroll: true, onFinish: () => setSubmitting(false), onSuccess: () => setEmail('') },
        );
    }

    return (
        <div className="flex flex-col rounded-sm border border-border p-6">
            <Badge variant="secondary" className="w-fit text-[10px] font-semibold tracking-widest uppercase">
                {plan.badge}
            </Badge>
            <h2 className="mt-3 font-serif text-2xl font-bold">{plan.title}</h2>
            <p className="mt-2 text-sm text-muted-foreground">{plan.description}</p>

            <form onSubmit={handleSubmit} className="mt-5 flex flex-col gap-2">
                <Input
                    type="email"
                    required
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                    placeholder="Your email address"
                />
                <Button type="submit" disabled={submitting} className="bg-red-600 hover:bg-red-700">
                    Subscribe
                </Button>
            </form>

            <Link href={plan.previewHref} className="mt-3 text-center text-sm text-muted-foreground underline underline-offset-2 hover:text-foreground">
                Preview Latest Edition
            </Link>
        </div>
    );
}
