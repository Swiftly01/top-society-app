<?php

namespace App\Services\Dashboard\Metrics;

use App\Services\Dashboard\Contracts\DashboardMetric;

/**
 * Sensible defaults (no icon, gray, no description, no trend) so a new
 * metric class only has to implement label() and value().
 */
abstract class BaseDashboardMetric implements DashboardMetric
{
    public function icon(): ?string
    {
        return null;
    }

    public function color(): string
    {
        return 'gray';
    }

    public function description(): ?string
    {
        return null;
    }

    public function trend(): ?array
    {
        return null;
    }
}
