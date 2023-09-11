<?php

namespace App\Filament\Resources\TicketResource\Widgets;

use App\Models\Ticket;
use Illuminate\Contracts\Support\Htmlable;
use App\Filament\CustomWidgets\MetricWidget;

class AnotherMetricWidget extends MetricWidget
{
    public ?string $filter = 'today';

    // protected string|Htmlable|null $description = "20k increase";

    // protected ?string $descriptionIcon = "heroicon-o-arrow-up";

    // protected array $extraAttributes = [
    //     'class' => 'cursor-pointer',
    // ];

    // protected ?string $url = "some url";

    protected string|Htmlable $label = "Total Tickets Created";

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'This week',
            'month' => 'This month',
            'year' => 'This year',
        ];
    }

    public function getValue(): ?string
    {
        return match ($this->filter) {
            'today' => Ticket::whereDate('created_at', today())->count(),
            'week' => Ticket::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'month' => Ticket::whereMonth('created_at', now()->month)->count(),
            'year' => Ticket::whereYear('created_at', now()->year)->count(),
        };
    }
}
