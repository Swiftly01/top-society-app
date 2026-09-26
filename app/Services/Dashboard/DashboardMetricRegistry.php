<?php

namespace App\Services\Dashboard;

use App\Services\Dashboard\Metrics\ActiveSponsoredFeaturesMetric;
use App\Services\Dashboard\Metrics\ActiveSubscribersMetric;
use App\Services\Dashboard\Metrics\AdminUsersMetric;
use App\Services\Dashboard\Metrics\AwaitingPublicationMetric;
use App\Services\Dashboard\Metrics\CategoriesMetric;
use App\Services\Dashboard\Metrics\MagazineDownloadsMetric;
use App\Services\Dashboard\Metrics\MediaStorageMetric;
use App\Services\Dashboard\Metrics\NewsletterEditionsSentMetric;
use App\Services\Dashboard\Metrics\PublishedArticlesMetric;
use App\Services\Dashboard\Metrics\TeamMembersMetric;

/**
 * To add a new dashboard stat card: write a class implementing
 * App\Services\Dashboard\Contracts\DashboardMetric (extend
 * Metrics\BaseDashboardMetric to skip the boilerplate), add its
 * DashboardMetricsService query method if it needs one, then list the
 * class here. App\Filament\Widgets\OverviewStatsWidget never changes.
 */
class DashboardMetricRegistry
{
    /**
     * @return array<class-string<\App\Services\Dashboard\Contracts\DashboardMetric>>
     */
    public static function cards(): array
    {
        return [
            PublishedArticlesMetric::class,
            AwaitingPublicationMetric::class,
            CategoriesMetric::class,
            ActiveSubscribersMetric::class,
            NewsletterEditionsSentMetric::class,
            ActiveSponsoredFeaturesMetric::class,
            MagazineDownloadsMetric::class,
            TeamMembersMetric::class,
            AdminUsersMetric::class,
            MediaStorageMetric::class,
        ];
    }
}
