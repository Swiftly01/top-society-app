<?php

namespace App\Services\Dashboard\Metrics;

use App\Services\Dashboard\DashboardMetricsService;

class ActiveSponsoredFeaturesMetric extends BaseDashboardMetric
{
    public function __construct(protected DashboardMetricsService $metrics) {}

    public function label(): string
    {
        return 'Active Sponsored Features';
    }

    public function value(): int
    {
        return $this->metrics->activeSponsoredFeaturesCount();
    }

    public function icon(): ?string
    {
        return 'heroicon-m-megaphone';
    }

    public function color(): string
    {
        return 'warning';
    }

    public function description(): ?string
    {
        return 'Currently live placements';
    }
}
