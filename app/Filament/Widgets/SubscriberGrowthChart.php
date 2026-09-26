<?php

namespace App\Filament\Widgets;

use App\Filament\Widgets\Concerns\BuildsChartFromSeries;
use App\Services\Dashboard\DashboardMetricsService;
use Filament\Widgets\ChartWidget;

class SubscriberGrowthChart extends ChartWidget
{
    use BuildsChartFromSeries;

    protected  ?string $heading = 'Subscriber Growth — Last 12 Months';

    protected  static ?int $sort = 2;

    protected int|string|array $columnSpan = 1;

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        $series = app(DashboardMetricsService::class)->subscriberGrowthByMonth(12);

        return [
            'labels' => $series['labels'],
            'datasets' => [
                $this->dataset('Active Subscribers', $series['data'], '#16a34a', filled: true),
            ],
        ];
    }
}
