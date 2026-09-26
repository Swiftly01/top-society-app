<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\BuildsChartFromSeries;
use App\Services\Dashboard\DashboardMetricsService;
use Filament\Widgets\ChartWidget;

class TopArticlesByViewsChart extends ChartWidget
{
    use BuildsChartFromSeries;

    protected  ?string $heading = 'Most-Read Articles — All Time';

    protected static ?int $sort = 5;

    protected int|string|array $columnSpan = 1;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $series = app(DashboardMetricsService::class)->topArticlesByViews(10);

        return [
            'labels' => $series['labels'],
            'datasets' => [
                $this->dataset('Views', $series['data'], '#0ea5e9'),
            ],
        ];
    }

    protected function getOptions(): array
    {
        return [
            'indexAxis' => 'y',
        ];
    }
}
