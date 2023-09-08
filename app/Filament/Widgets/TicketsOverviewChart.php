<?php

namespace App\Filament\Widgets;

use App\Models\Ticket;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\ChartWidget;

class TicketsOverviewChart extends ChartWidget
{
    protected static ?string $heading = 'Tickets Overview';

    protected static ?int $sort = 2;

    public ?string $filter = 'week';

    protected static ?string $pollingInterval = null;

    public function getDescription(): ?string
    {
        $label = null;

        switch ($this->filter) {
            case 'week':
                $label = 'last week';
                break;
            case 'month':
                $label = 'last month';
                break;
            case 'year':
                $label = 'this year';
                break;
        }

        return "The number of tickets created {$label}.";
    }

    protected function getFilters(): ?array
    {
        return [
            'week' => 'Last week',
            'month' => 'Last month',
            'year' => 'This year',
        ];
    }

    protected function getData(): array
    {
        $start = null;
        $end = null;
        $perData = null;

        switch ($this->filter) {
            case 'week':
                $start = now()->startOfWeek();
                $end = now()->endOfWeek();
                $perData = 'perDay';
                break;
            case 'month':
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
                $perData = 'perDay';
                break;
            case 'year':
                $start = now()->startOfYear();
                $end = now()->endOfYear();
                $perData = 'perMonth';
                break;
        }

        $data = Trend::model(Ticket::class)
                    ->between(
                        start: $start,
                        end: $end,
                    )
            ->$perData()
                ->count();

        return [
            'datasets' => [
                [
                    'label' => 'Blog posts',
                    'data' => $data->map(fn(TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $data->map(fn(TrendValue $value) => $value->date),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
