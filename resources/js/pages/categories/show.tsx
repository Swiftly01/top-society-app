import { FormEvent, useState } from "react";
import { Head, Link, router } from "@inertiajs/react";
import { ArrowRight, Mail } from "lucide-react";
import { ArticleCard } from "@/components/site/article-card";
import { ArticleMedia } from "@/components/site/article-media";
import { SiteFooter } from "@/components/site/site-footer";
import { SiteHeader } from "@/components/site/site-header";
import {
    Breadcrumb,
    BreadcrumbItem,
    BreadcrumbLink,
    BreadcrumbList,
    BreadcrumbPage,
    BreadcrumbSeparator,
} from "@/components/ui/breadcrumb";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import type { CategoryPageProps } from "@/types/category";

export default function CategoryShow(props: CategoryPageProps) {
    const {
        name,
        activeNav,
        breadcrumb,
        featuredArticle,
        secondaryArticles,
        briefing,
        topics,
    } = props;
    const [email, setEmail] = useState("");
    const [submitting, setSubmitting] = useState(false);

    function handleSubmit(e: FormEvent) {
        e.preventDefault();
        if (!email || submitting) return;
        setSubmitting(true);
        router.post(
            "/newsletter",
            { email },
            {
                preserveScroll: true,
                onFinish: () => setSubmitting(false),
                onSuccess: () => setEmail(""),
            },
        );
    }

    return (
        <>
            <Head title={name} />

            <div className="min-h-screen bg-background text-foreground">
                <SiteHeader activeNav={activeNav} nav="section" />

                <main className="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
                    <Breadcrumb>
                        <BreadcrumbList>
                            {breadcrumb.map((crumb, i) => (
                                <div
                                    key={crumb.href}
                                    className="flex items-center gap-1.5"
                                >
                                    <BreadcrumbItem>
                                        {i === breadcrumb.length - 1 ? (
                                            <BreadcrumbPage>
                                                {crumb.label}
                                            </BreadcrumbPage>
                                        ) : (
                                            <BreadcrumbLink asChild>
                                                <Link href={crumb.href}>
                                                    {crumb.label}
                                                </Link>
                                            </BreadcrumbLink>
                                        )}
                                    </BreadcrumbItem>
                                    {i < breadcrumb.length - 1 && (
                                        <BreadcrumbSeparator />
                                    )}
                                </div>
                            ))}
                        </BreadcrumbList>
                    </Breadcrumb>

                    <h1 className="mt-4 border-b border-border pb-6 font-serif text-4xl font-bold sm:text-5xl">
                        {name}
                    </h1>

                    <div className="mt-8 grid gap-10 lg:grid-cols-[1fr_300px]">
                        {featuredArticle ? (
                            <Link
                                href={featuredArticle.href}
                                className="group flex flex-col"
                            >
                                <ArticleMedia
                                    src={featuredArticle.image}
                                    alt={featuredArticle.title}
                                    className="aspect-[16/9] w-full rounded-sm"
                                />
                                <span className="mt-4 text-[11px] font-semibold tracking-widest text-red-600 uppercase">
                                    {featuredArticle.category}
                                </span>
                                <h2 className="mt-1 font-serif text-3xl leading-tight font-bold group-hover:underline">
                                    {featuredArticle.title}
                                </h2>
                                {featuredArticle.excerpt && (
                                    <p className="mt-2 max-w-2xl text-sm text-muted-foreground">
                                        {featuredArticle.excerpt}
                                    </p>
                                )}
                                <p className="mt-3 text-xs text-muted-foreground">
                                    {featuredArticle.author && (
                                        <>By {featuredArticle.author} · </>
                                    )}
                                    {featuredArticle.readTime}
                                </p>
                            </Link>
                        ) : (
                            <div className="flex flex-col items-start justify-center rounded-sm border border-dashed border-border p-10 text-sm text-muted-foreground">
                                No stories published in this category yet.
                            </div>
                        )}

                        {/* Sidebar: briefing signup + topics */}
                        <aside className="flex flex-col gap-8">
                            <div className="rounded-sm border border-border bg-muted/40 p-5">
                                <div className="mb-2 flex items-center gap-2 text-xs font-semibold tracking-widest text-foreground uppercase">
                                    <Mail className="size-4" />
                                    {briefing.heading}
                                </div>
                                <p className="text-sm text-muted-foreground">
                                    {briefing.description}
                                </p>
                                <form
                                    onSubmit={handleSubmit}
                                    className="mt-3 flex flex-col gap-2"
                                >
                                    <Input
                                        type="email"
                                        required
                                        value={email}
                                        onChange={(e) =>
                                            setEmail(e.target.value)
                                        }
                                        placeholder="Email Address"
                                    />
                                    <Button
                                        type="submit"
                                        disabled={submitting}
                                        className="bg-red-600 hover:bg-red-700"
                                    >
                                        {briefing.ctaLabel}
                                    </Button>
                                </form>
                            </div>

                            <div>
                                <h3 className="mb-3 border-b border-border pb-2 text-xs font-semibold tracking-widest text-muted-foreground uppercase">
                                    {topics.heading}
                                </h3>
                                <ul className="flex flex-col divide-y divide-border">
                                    {topics.links.map((topic) => (
                                        <li key={topic.href}>
                                            <Link
                                                href={topic.href}
                                                className="group flex items-center justify-between py-3 text-sm font-medium"
                                            >
                                                {topic.label}
                                                <ArrowRight className="size-4 text-muted-foreground transition-transform group-hover:translate-x-0.5 group-hover:text-red-600" />
                                            </Link>
                                        </li>
                                    ))}
                                </ul>
                            </div>
                        </aside>
                    </div>

                    {/* Secondary stories */}
                    <div className="mt-12 grid gap-x-6 gap-y-8 border-t border-border pt-10 sm:grid-cols-3">
                        {secondaryArticles.map((article) => (
                            <ArticleCard key={article.id} article={article} />
                        ))}
                    </div>
                </main>

                <SiteFooter />
            </div>
        </>
    );
}
