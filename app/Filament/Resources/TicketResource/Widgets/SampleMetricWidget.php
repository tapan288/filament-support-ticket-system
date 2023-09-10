<?php

namespace App\Filament\Resources\TicketResource\Widgets;

use Illuminate\Contracts\Support\Htmlable;
use App\Filament\CustomWidgets\MetricWidget;

class SampleMetricWidget extends MetricWidget
{
    public ?string $filter = 'today';

    protected string|Htmlable|null $description = "20k increase";

    protected ?string $descriptionIcon = "heroicon-o-arrow-up";

    // protected array $extraAttributes = [
    //     'class' => 'cursor-pointer',
    // ];

    // protected ?string $url = "some url";

    protected string|Htmlable $label = "Total Tickets";

    protected function getFilters(): ?array
    {
        return [
            'today' => 'Today',
            'week' => 'This week',
            'month' => 'This month',
            'year' => 'This year',
        ];
    }

    public function getValue()
    {
        return "some value";
    }
}
