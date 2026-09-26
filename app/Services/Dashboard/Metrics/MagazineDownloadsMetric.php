<?php

namespace App\Services\Dashboard\Metrics;

use App\Services\Dashboard\DashboardMetricsService;

class MagazineDownloadsMetric extends BaseDashboardMetric
{
    public function __construct(protected DashboardMetricsService $metrics) {}

    public function label(): string
    {
        return 'Magazine Downloads';
    }

    public function value(): int
    {
        return $this->metrics->magazineDownloadsTotal();
    }

    public function icon(): ?string
    {
        return 'heroicon-m-arrow-down-tray';
    }

    public function color(): string
    {
        return 'info';
    }

    public function description(): ?string
    {
        return 'Across '.$this->metrics->publishedMagazinesCount().' published issues';
    }
}
