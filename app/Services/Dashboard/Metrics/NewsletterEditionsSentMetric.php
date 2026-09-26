<?php

namespace App\Services\Dashboard\Metrics;

use App\Services\Dashboard\DashboardMetricsService;

class NewsletterEditionsSentMetric extends BaseDashboardMetric
{
    public function __construct(protected DashboardMetricsService $metrics) {}

    public function label(): string
    {
        return 'Newsletter Editions Sent';
    }

    public function value(): int
    {
        return $this->metrics->newsletterEditionsSentCount();
    }

    public function icon(): ?string
    {
        return 'heroicon-m-paper-airplane';
    }

    public function color(): string
    {
        return 'primary';
    }

    public function description(): ?string
    {
        $latest = $this->metrics->latestNewsletterEditionSentAt();

        return $latest ? 'Latest: '.$latest->diffForHumans() : 'None sent yet';
    }
}
