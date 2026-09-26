<?php

namespace App\Filament\Widgets;

use App\Services\Dashboard\Contracts\DashboardMetric;
use App\Services\Dashboard\DashboardMetricRegistry;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

/**
 * Purely presentational: it just asks DashboardMetricRegistry for the
 * list of metric classes and turns each into a Stat. Nothing here knows
 * how any individual number is calculated — see
 * App\Services\Dashboard\DashboardMetricsService for that. Adding,
 * removing, or reordering a card is done in the registry, never here.
 */
class OverviewStatsWidget extends StatsOverviewWidget
{
    protected static ?int $sort = -3;

    protected function getColumns(): int
    {
        return 5;
    }

    protected function getStats(): array
    {
        return collect(DashboardMetricRegistry::cards())
            ->map(fn (string $class) => app($class))
            ->map(function (DashboardMetric $metric) {
                $stat = Stat::make($metric->label(), $metric->value())
                    ->color($metric->color());

                if ($icon = $metric->icon()) {
                    $stat->descriptionIcon($icon);
                }

                if ($description = $metric->description()) {
                    $stat->description($description);
                }

                if ($trend = $metric->trend()) {
                    $stat->chart($trend);
                }

                return $stat;
            })
            ->all();
    }
}
