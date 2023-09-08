<?php

namespace App\Filament\Widgets;

use Closure;
use App\Models\Role;
use Filament\Tables;
use App\Models\Ticket;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextInputColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestTickets extends BaseWidget
{
    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 'full';

    protected function getTableQuery(): Builder
    {
        return auth()->user()->hasRole(Role::ROLES['Admin']) ? Ticket::query()
            ->latest()
            ->limit(5) : Ticket::query()
                ->latest()
                ->where('assigned_to', auth()->id())
                ->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('title')
                ->sortable()
                ->description(fn(Ticket $record): string => $record?->description ?? ''),
            TextColumn::make('status')
                ->sortable()
                ->colors([
                    'warning' => Ticket::STATUS['Archived'],
                    'success' => Ticket::STATUS['Closed'],
                    'danger' => Ticket::STATUS['Open'],
                ])
                ->badge(),
            TextColumn::make('priority')
                ->sortable()
                ->colors([
                    'warning' => Ticket::PRIORITY['Medium'],
                    'success' => Ticket::PRIORITY['Low'],
                    'danger' => Ticket::PRIORITY['High'],
                ])
                ->badge(),
            TextColumn::make('assignedTo.name'),
            TextColumn::make('assignedBy.name'),
            TextInputColumn::make('comment'),
            TextColumn::make('created_at')
                ->sortable()
                ->dateTime(),
        ];
    }

    protected function isTablePaginationEnabled(): bool
    {
        return false;
    }
}
