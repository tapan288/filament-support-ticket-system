<?php

namespace App\Filament\Resources\LabelResource\Pages;

use Filament\Pages\Actions;
use App\Filament\Resources\LabelResource;
use Filament\Resources\Pages\ListRecords;

class ListLabels extends ListRecords
{
    protected static string $resource = LabelResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
