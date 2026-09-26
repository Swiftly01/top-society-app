<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\BuildsChartFromSeries;
use App\Services\Dashboard\DashboardMetricsService;
use Filament\Widgets\ChartWidget;

class NewsletterEditionsSentChart extends ChartWidget
{
    use BuildsChartFromSeries;

    protected  ?string $heading = 'Newsletter Editions Sent — Last 12 Months';

    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 1;

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getData(): array
    {
        $series = app(DashboardMetricsService::class)->newsletterEditionsSentByMonth(12);

        return [
            'labels' => $series['labels'],
            'datasets' => [
                $this->dataset('Editions Sent', $series['data'], '#7c3aed'),
            ],
        ];
    }
}
