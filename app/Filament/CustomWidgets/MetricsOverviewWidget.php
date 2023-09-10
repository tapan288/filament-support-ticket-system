<?php

namespace Filament\Widgets;

use Filament\Widgets\Concerns\CanPoll;
use App\Filament\CustomWidgets\MetricWidget;

class MetricsOverviewWidget extends Widget
{
    use CanPoll;

    /**
     * @var array<MetricWidget> | null
     */
    protected ?array $cachedStats = null;

    protected int|string|array $columnSpan = 'full';

    /**
     * @var view-string
     */
    protected static string $view = 'filament.custom-widgets.metrics-overview-widget';

    protected function getColumns(): int
    {
        $count = count($this->getCachedStats());

        if ($count < 3) {
            return 3;
        }

        if (($count % 3) !== 1) {
            return 3;
        }

        return 4;
    }

    /**
     * @return array<MetricWidget>
     */
    protected function getCachedStats(): array
    {
        return $this->cachedStats ??= $this->getMetrics();
    }

    /**
     * @return array<MetricWidget>
     */
    protected function getMetrics(): array
    {
        return [];
    }
}
