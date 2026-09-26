<?php

namespace App\Services\Dashboard\Metrics;

use App\Services\Dashboard\DashboardMetricsService;

class PublishedArticlesMetric extends BaseDashboardMetric
{
    public function __construct(protected DashboardMetricsService $metrics) {}

    public function label(): string
    {
        return 'Published Articles';
    }

    public function value(): int
    {
        return $this->metrics->publishedArticlesCount();
    }

    public function icon(): ?string
    {
        return 'heroicon-m-check-circle';
    }

    public function color(): string
    {
        return 'success';
    }

    public function description(): ?string
    {
        return $this->metrics->awaitingPublicationCount().' awaiting publication';
    }
}
