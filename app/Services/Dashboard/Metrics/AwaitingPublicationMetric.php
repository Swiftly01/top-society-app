<?php

namespace App\Services\Dashboard\Metrics;

use App\Services\Dashboard\DashboardMetricsService;

class AwaitingPublicationMetric extends BaseDashboardMetric
{
    public function __construct(protected DashboardMetricsService $metrics) {}

    public function label(): string
    {
        return 'Awaiting Publication';
    }

    public function value(): int
    {
        return $this->metrics->awaitingPublicationCount();
    }

    public function icon(): ?string
    {
        return 'heroicon-m-pencil-square';
    }

    public function color(): string
    {
        return 'warning';
    }

    public function description(): ?string
    {
        return 'Draft, in review, or scheduled';
    }
}
