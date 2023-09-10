<?php

namespace App\Filament\Resources\TicketResource\Widgets;

use App\Filament\CustomWidgets\MetricsOverviewWidget;

class MetricsOverview extends MetricsOverviewWidget
{
    protected static ?string $pollingInterval = null;

    protected function getMetrics(): array
    {
        return [
            SampleMetricWidget::class,
        ];
    }
}
