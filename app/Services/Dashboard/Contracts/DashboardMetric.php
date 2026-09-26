<?php

namespace App\Services\Dashboard\Contracts;

/**
 * One dashboard stat card. Implementations are thin: they call one or two
 * DashboardMetricsService methods and format the result — all querying
 * stays in the service. Register a new implementation in
 * DashboardMetricRegistry::cards() and it appears on the dashboard with
 * no widget changes.
 */
interface DashboardMetric
{
    public function label(): string;

    public function value(): string|int|float;

    /**
     * A Heroicon name (e.g. "heroicon-m-check-circle"), or null for none.
     */
    public function icon(): ?string;

    /**
     * A Filament color name: primary, success, warning, danger, info, or gray.
     */
    public function color(): string;

    public function description(): ?string;

    /**
     * Optional sparkline series for Stat::chart(). Null hides it.
     *
     * @return array<int, int>|null
     */
    public function trend(): ?array;
}
