<?php

namespace App\Services\Dashboard\Metrics;

use App\Services\Dashboard\DashboardMetricsService;

class AdminUsersMetric extends BaseDashboardMetric
{
    public function __construct(protected DashboardMetricsService $metrics) {}

    public function label(): string
    {
        return 'Admin Users';
    }

    public function value(): int
    {
        return $this->metrics->adminUsersCount();
    }

    public function icon(): ?string
    {
        return 'heroicon-m-shield-check';
    }

    public function description(): ?string
    {
        return 'With access to this dashboard';
    }
}
