<?php

namespace App\Services\Dashboard\Metrics;

use App\Services\Dashboard\DashboardMetricsService;

class CategoriesMetric extends BaseDashboardMetric
{
    public function __construct(protected DashboardMetricsService $metrics) {}

    public function label(): string
    {
        return 'Categories';
    }

    public function value(): int
    {
        return $this->metrics->categoriesCount();
    }

    public function icon(): ?string
    {
        return 'heroicon-m-rectangle-stack';
    }

    public function color(): string
    {
        return 'info';
    }
}
