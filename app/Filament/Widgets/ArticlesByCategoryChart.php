<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\BuildsChartFromSeries;
use App\Services\Dashboard\DashboardMetricsService;
use Filament\Widgets\ChartWidget;

class ArticlesByCategoryChart extends ChartWidget
{
    use BuildsChartFromSeries;

    protected  ?string $heading = 'Articles by Category';

    protected static  ?int $sort = 4;

    protected int|string|array $columnSpan = 1;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $series = app(DashboardMetricsService::class)->articlesByCategory(8);

        return [
            'labels' => $series['labels'],
            'datasets' => [
                $this->dataset('Published Articles', $series['data'], '#dc2626'),
            ],
        ];
    }
}
