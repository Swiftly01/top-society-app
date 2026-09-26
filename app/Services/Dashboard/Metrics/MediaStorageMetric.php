<?php

namespace App\Services\Dashboard\Metrics;

use App\Models\Media;
use App\Services\Dashboard\DashboardMetricsService;

class MediaStorageMetric extends BaseDashboardMetric
{
    public function __construct(protected DashboardMetricsService $metrics) {}

    public function label(): string
    {
        return 'Media Storage Used';
    }

    public function value(): string
    {
        return Media::formatBytes($this->metrics->mediaStorageBytes());
    }

    public function icon(): ?string
    {
        return 'heroicon-m-photo';
    }

    public function color(): string
    {
        return 'primary';
    }

    public function description(): ?string
    {
        return $this->metrics->mediaFilesCount().' files across the library';
    }
}
