<?php

namespace App\Filament\Widgets\Concerns;

/**
 * Every dashboard chart widget calls a DashboardMetricsService method
 * that returns ['labels' => [...], 'data' => [...]] and turns it into a
 * Chart.js dataset via this trait, so line/bar colors and styling stay
 * consistent across the dashboard without repeating them in each widget.
 */
trait BuildsChartFromSeries
{
    /**
     * @param  array<int, int|float>  $data
     * @return array<string, mixed>
     */
    protected function dataset(string $label, array $data, string $color = '#dc2626', bool $filled = false): array
    {
        return [
            'label' => $label,
            'data' => $data,
            'borderColor' => $color,
            'backgroundColor' => $filled ? $color.'33' : $color,
            'fill' => $filled,
            'tension' => 0.35,
        ];
    }
}
