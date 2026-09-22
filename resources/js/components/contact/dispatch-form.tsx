import { Form } from '@inertiajs/react';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Textarea } from '@/components/ui/textarea';
import type { DispatchRoutingOption, DispatchStat } from '@/types/contact';

interface DispatchFormProps {
    eyebrow: string;
    heading: string;
    description: string;
    stats: DispatchStat[];
    routingOptions: DispatchRoutingOption[];
    consentLabel: string;
    submitLabel: string;
}

export function DispatchForm({
    eyebrow,
    heading,
    description,
    stats,
    routingOptions,
    consentLabel,
    submitLabel,
}: DispatchFormProps) {
    return (
        <section className="mx-auto max-w-7xl px-4 py-14 sm:px-6 lg:px-8">
            <span className="text-[11px] font-semibold tracking-widest text-red-600 uppercase">{eyebrow}</span>
            <h2 className="mt-1 font-serif text-3xl font-bold">{heading}</h2>
            <p className="mt-2 max-w-2xl text-sm text-muted-foreground">{description}</p>

            <div className="mt-8 grid gap-10 lg:grid-cols-[260px_1fr]">
                <div className="rounded-sm border border-border p-5">
                    <p className="mb-3 text-[11px] font-semibold tracking-widest text-red-600 uppercase">
                        Dispatch Resolution Specs
                    </p>
                    <dl className="flex flex-col divide-y divide-border">
                        {stats.map((stat) => (
                            <div key={stat.label} className="flex items-center justify-between py-2 text-sm">
                                <dt className="text-muted-foreground">{stat.label}</dt>
                                <dd className={stat.live ? 'font-semibold text-red-600' : 'font-semibold'}>
                                    {stat.value}
                                </dd>
                            </div>
                        ))}
                    </dl>
                </div>

                <Form
                    action="/contact"
                    method="post"
                    encType="multipart/form-data"
                    resetOnSuccess
                    className="flex flex-col gap-5"
                >
                    {({ processing, errors, wasSuccessful }) => (
                        <>
                            <div className="grid gap-5 sm:grid-cols-2">
                                <div className="grid gap-2">
                                    <Label htmlFor="name">Full Name / Alias</Label>
                                    <Input id="name" name="name" required placeholder="e.g. Chief Adeleke / Confidential" />
                                    <InputError message={errors.name} />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="email">Email Address / ProtonMail</Label>
                                    <Input id="email" name="email" type="email" required placeholder="name@domain.com" />
                                    <InputError message={errors.email} />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="organization">Organization / Affiliation (Optional)</Label>
                                    <Input id="organization" name="organization" placeholder="e.g. Financial Advisory / Independent" />
                                    <InputError message={errors.organization} />
                                </div>
                                <div className="grid gap-2">
                                    <Label htmlFor="subject">Dispatch Subject Line</Label>
                                    <Input id="subject" name="subject" required placeholder="Concise dispatch overview" />
                                    <InputError message={errors.subject} />
                                </div>
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="routing">Select Departmental Routing</Label>
                                <Select name="routing" defaultValue={routingOptions[0]?.value}>
                                    <SelectTrigger id="routing" className="w-full">
                                        <SelectValue />
                                    </SelectTrigger>
                                    <SelectContent>
                                        {routingOptions.map((option) => (
                                            <SelectItem key={option.value} value={option.value}>
                                                {option.label}
                                            </SelectItem>
                                        ))}
                                    </SelectContent>
                                </Select>
                                <InputError message={errors.routing} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="content">Detailed Dispatch Content</Label>
                                <Textarea
                                    id="content"
                                    name="content"
                                    required
                                    rows={6}
                                    placeholder="Provide granular context, timeline references, involved parties, or specific commercial scope..."
                                />
                                <InputError message={errors.content} />
                            </div>

                            <div className="grid gap-2">
                                <Label htmlFor="attachment">Encrypted Evidence &amp; Dossier Attachment</Label>
                                <Input id="attachment" name="attachment" type="file" />
                                <p className="text-xs text-muted-foreground">PDF, ZIP, PNG, MP3 up to 45MB. Stripped of EXIF metadata automatically.</p>
                                <InputError message={errors.attachment} />
                            </div>

                            <label className="flex items-start gap-2 text-sm text-muted-foreground">
                                <Checkbox name="consent" required className="mt-0.5" />
                                {consentLabel}
                            </label>
                            <InputError message={errors.consent} />

                            <Button type="submit" disabled={processing} className="w-fit bg-red-600 hover:bg-red-700">
                                {submitLabel}
                            </Button>

                            {wasSuccessful && (
                                <p className="text-sm font-medium text-green-600">
                                    Dispatch received. Our senior bureau desk will respond within the stated window.
                                </p>
                            )}
                        </>
                    )}
                </Form>
            </div>
        </section>
    );
}
