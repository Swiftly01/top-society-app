<?php

namespace App\Services\Dashboard\Metrics;

use App\Services\Dashboard\DashboardMetricsService;

class TeamMembersMetric extends BaseDashboardMetric
{
    public function __construct(protected DashboardMetricsService $metrics) {}

    public function label(): string
    {
        return 'Team Members';
    }

    public function value(): int
    {
        return $this->metrics->activeTeamMembersCount();
    }

    public function icon(): ?string
    {
        return 'heroicon-m-identification';
    }

    public function description(): ?string
    {
        return 'Active profiles on the About page';
    }
}
