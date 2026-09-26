<?php

namespace App\Filament\Widgets;

use App\Services\Dashboard\DashboardMetricsService;
use Filament\Widgets\ChartWidget;

class ArticlesByStatusChart extends ChartWidget
{
    protected  ?string $heading = 'Content by Status';

    protected static  ?int $sort = 3;

    protected int|string|array $columnSpan = 1;

    protected function getType(): string
    {
        return 'doughnut';
    }

    protected function getData(): array
    {
        $series = app(DashboardMetricsService::class)->articlesByStatus();

        return [
            'labels' => $series['labels'],
            'datasets' => [
                [
                    'data' => $series['data'],
                    // Draft, Pending Review, Scheduled, Published, Archived —
                    // matches ArticleStatus::getColor()'s intent in spirit.
                    'backgroundColor' => ['#9ca3af', '#f59e0b', '#3b82f6', '#16a34a', '#dc2626'],
                ],
            ],
        ];
    }
}
