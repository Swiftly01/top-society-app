<?php

namespace App\Services\Dashboard\Metrics;

use App\Services\Dashboard\DashboardMetricsService;

class ActiveSubscribersMetric extends BaseDashboardMetric
{
    protected const TREND_DAYS = 7;

    public function __construct(protected DashboardMetricsService $metrics) {}

    public function label(): string
    {
        return 'Active Subscribers';
    }

    public function value(): int
    {
        return $this->metrics->activeSubscribersCount();
    }

    public function icon(): ?string
    {
        return 'heroicon-m-users';
    }

    public function color(): string
    {
        return 'success';
    }

    public function description(): ?string
    {
        return '+'.$this->metrics->newSubscribersCount(self::TREND_DAYS).' in the last '.self::TREND_DAYS.' days';
    }

    public function trend(): ?array
    {
        return $this->metrics->activeSubscribersTrend(self::TREND_DAYS);
    }
}
