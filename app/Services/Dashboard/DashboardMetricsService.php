<?php

namespace App\Services\Dashboard;

use App\Enums\ArticleStatus;
use App\Repositories\Contracts\ArticleRepositoryInterface;
use App\Repositories\Contracts\CategoryRepositoryInterface;
use App\Repositories\Contracts\MagazineRepositoryInterface;
use App\Repositories\Contracts\MediaRepositoryInterface;
use App\Repositories\Contracts\NewsletterEditionRepositoryInterface;
use App\Repositories\Contracts\NewsletterSubscriptionRepositoryInterface;
use App\Repositories\Contracts\SponsoredFeatureRepositoryInterface;
use App\Repositories\Contracts\TeamMemberRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * Single source of truth for every number the admin dashboard shows.
 *
 * This is the extension point for the whole dashboard: a new stat card or
 * chart never needs a bespoke repository method — add one method here
 * (built on `$repository->newQuery()`) and a small Filament widget class
 * to render it. Nothing else in the app depends on this service, so it's
 * free to grow without risking anything outside `app/Filament/Widgets`
 * and `app/Services/Dashboard/Metrics`.
 *
 * Every trend/breakdown method returns the same shape —
 * `['labels' => string[], 'data' => (int|float)[]]` — so chart widgets
 * are interchangeable boilerplate around whichever method they call.
 */
class DashboardMetricsService
{
    public function __construct(
        protected ArticleRepositoryInterface $articles,
        protected CategoryRepositoryInterface $categories,
        protected NewsletterSubscriptionRepositoryInterface $subscriptions,
        protected NewsletterEditionRepositoryInterface $editions,
        protected SponsoredFeatureRepositoryInterface $sponsoredFeatures,
        protected MagazineRepositoryInterface $magazines,
        protected TeamMemberRepositoryInterface $teamMembers,
        protected UserRepositoryInterface $users,
        protected MediaRepositoryInterface $media,
    ) {}

    // --- Stat card figures --------------------------------------------

    public function publishedArticlesCount(): int
    {
        return $this->articles->newQuery()->published()->count();
    }

    /**
     * Draft, pending review, or scheduled — everything not live yet.
     */
    public function awaitingPublicationCount(): int
    {
        return $this->articles->newQuery()
            ->whereIn('status', [
                ArticleStatus::Draft->value,
                ArticleStatus::PendingReview->value,
                ArticleStatus::Scheduled->value,
            ])
            ->count();
    }

    public function categoriesCount(): int
    {
        return $this->categories->newQuery()->count();
    }

    public function activeSubscribersCount(): int
    {
        return $this->subscriptions->newQuery()->active()->count();
    }

    public function newSubscribersCount(int $days = 7): int
    {
        return $this->subscriptions->newQuery()
            ->where('subscribed_at', '>=', now()->subDays($days))
            ->count();
    }

    /**
     * One count per day for the last $days days — a Stat::chart() sparkline.
     *
     * @return array<int, int>
     */
    public function activeSubscribersTrend(int $days = 7): array
    {
        $counts = $this->subscriptions->newQuery()
            ->active()
            ->where('subscribed_at', '>=', now()->subDays($days - 1)->startOfDay())
            ->selectRaw('DATE(subscribed_at) as d, COUNT(*) as total')
            ->groupBy('d')
            ->pluck('total', 'd');

        return collect(range($days - 1, 0))
            ->map(fn (int $offset) => (int) ($counts[now()->subDays($offset)->toDateString()] ?? 0))
            ->all();
    }

    public function newsletterEditionsSentCount(): int
    {
        return $this->editions->newQuery()->sent()->count();
    }

    public function latestNewsletterEditionSentAt(): ?Carbon
    {
        $sentAt = $this->editions->newQuery()->sent()->max('sent_at');

        return $sentAt ? Carbon::parse($sentAt) : null;
    }

    public function activeSponsoredFeaturesCount(): int
    {
        return $this->sponsoredFeatures->newQuery()->active()->count();
    }

    public function magazineDownloadsTotal(): int
    {
        return (int) $this->magazines->newQuery()->sum('downloads_count');
    }

    public function publishedMagazinesCount(): int
    {
        return $this->magazines->newQuery()->published()->count();
    }

    public function activeTeamMembersCount(): int
    {
        return $this->teamMembers->newQuery()->active()->count();
    }

    public function adminUsersCount(): int
    {
        return $this->users->newQuery()->count();
    }

    public function mediaStorageBytes(): int
    {
        return (int) $this->media->newQuery()->sum('size');
    }

    public function mediaFilesCount(): int
    {
        return $this->media->newQuery()->count();
    }

    // --- Charts ---------------------------------------------------------

    /**
     * @return array{labels: string[], data: int[]}
     */
    public function articlesPublishedByMonth(int $months = 12): array
    {
        return $this->monthlySeries($this->articles->newQuery()->published(), 'published_at', $months);
    }

    /**
     * @return array{labels: string[], data: int[]}
     */
    public function newsletterEditionsSentByMonth(int $months = 12): array
    {
        return $this->monthlySeries($this->editions->newQuery()->sent(), 'sent_at', $months);
    }

    /**
     * Cumulative active-subscriber count at the end of each of the last
     * $months months — a running total, not new signups per month, so the
     * line reads as overall audience growth.
     *
     * @return array{labels: string[], data: int[]}
     */
    public function subscriberGrowthByMonth(int $months = 12): array
    {
        $since = now()->subMonths($months - 1)->startOfMonth();

        $monthly = $this->monthlySeries(
            $this->subscriptions->newQuery()->active(),
            'subscribed_at',
            $months,
        );

        $baseline = $this->subscriptions->newQuery()
            ->active()
            ->where('subscribed_at', '<', $since)
            ->count();

        $running = $baseline;
        $cumulative = collect($monthly['data'])
            ->map(function (int $newInMonth) use (&$running) {
                $running += $newInMonth;

                return $running;
            })
            ->all();

        return ['labels' => $monthly['labels'], 'data' => $cumulative];
    }

    /**
     * @return array{labels: string[], data: int[]}
     */
    public function articlesByStatus(): array
    {
        $counts = $this->articles->newQuery()
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return [
            'labels' => collect(ArticleStatus::cases())->map->getLabel()->all(),
            'data' => collect(ArticleStatus::cases())
                ->map(fn (ArticleStatus $status) => (int) ($counts[$status->value] ?? 0))
                ->all(),
        ];
    }

    /**
     * Top categories by published article count.
     *
     * @return array{labels: string[], data: int[]}
     */
    public function articlesByCategory(int $limit = 8): array
    {
        $rows = $this->categories->newQuery()
            ->withCount(['articles' => fn (Builder $q) => $q->published()])
            ->orderByDesc('articles_count')
            ->limit($limit)
            ->get(['id', 'name']);

        return [
            'labels' => $rows->pluck('name')->all(),
            'data' => $rows->pluck('articles_count')->all(),
        ];
    }

    /**
     * Most-viewed articles of all time.
     *
     * @return array{labels: string[], data: int[]}
     */
    public function topArticlesByViews(int $limit = 10): array
    {
        $rows = $this->articles->newQuery()
            ->orderByDesc('views_count')
            ->limit($limit)
            ->get(['title', 'views_count']);

        return [
            'labels' => $rows->pluck('title')->map(fn (string $title) => Str::limit($title, 42))->all(),
            'data' => $rows->pluck('views_count')->all(),
        ];
    }

    // --- Shared helpers ---------------------------------------------

    /**
     * Groups $query by month over $dateColumn for the trailing $months
     * months, zero-filling any month with no rows so every chart has a
     * continuous, evenly-spaced axis. $dateColumn is always a hardcoded
     * value supplied by this class's own methods, never user input.
     *
     * @return array{labels: string[], data: int[]}
     */
    protected function monthlySeries(Builder $query, string $dateColumn, int $months): array
    {
        $since = now()->subMonths($months - 1)->startOfMonth();

        $counts = (clone $query)
            ->where($dateColumn, '>=', $since)
            ->selectRaw("DATE_FORMAT($dateColumn, '%Y-%m') as ym, COUNT(*) as total")
            ->groupBy('ym')
            ->pluck('total', 'ym');

        $labels = [];
        $data = [];

        for ($i = $months - 1; $i >= 0; $i--) {
            $month = now()->subMonths($i);
            $labels[] = $month->format('M Y');
            $data[] = (int) ($counts[$month->format('Y-m')] ?? 0);
        }

        return ['labels' => $labels, 'data' => $data];
    }
}
