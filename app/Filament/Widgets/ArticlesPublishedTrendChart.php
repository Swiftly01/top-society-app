<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\BuildsChartFromSeries;
use App\Services\Dashboard\DashboardMetricsService;
use Filament\Widgets\ChartWidget;

class ArticlesPublishedTrendChart extends ChartWidget
{
    use BuildsChartFromSeries;

    protected  ?string $heading = 'Articles Published — Last 12 Months';

    protected static ?int $sort = 1;

    protected int|string|array $columnSpan = 1;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $series = app(DashboardMetricsService::class)->articlesPublishedByMonth(12);

        return [
            'labels' => $series['labels'],
            'datasets' => [
                $this->dataset('Articles Published', $series['data'], '#dc2626', filled: true),
            ],
        ];
    }
}
