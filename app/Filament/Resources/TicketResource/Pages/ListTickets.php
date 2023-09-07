<?php

namespace App\Filament\Resources\TicketResource\Pages;

use App\Filament\Widgets\StatsOverview;
use App\Models\Role;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\TicketResource;

class ListTickets extends ListRecords
{
    protected static string $resource = TicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getTableQuery(): Builder
    {
        return auth()->user()->hasRole(Role::ROLES['Admin']) ? parent::getTableQuery() : parent::getTableQuery()->where('assigned_to', auth()->id());
    }

    protected function getHeaderWidgets(): array
    {
        return [
            StatsOverview::make(),
        ];
    }
}
