<?php

namespace App\Filament\Widgets;

use App\Models\Role;
use App\Models\User;
use App\Models\Label;
use App\Models\Ticket;
use App\Models\Category;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getCards(): array
    {
        return [
            Stat::make('Total Tickets', Ticket::count()),
            Stat::make('Total Agents', User::whereHas('roles', function ($query) {
                $query->where('title', Role::ROLES['Agent']);
            })->count()),
            Stat::make('Total Categories', Category::count()),
            Stat::make('Total Labels', Label::count()),
        ];
    }
}
