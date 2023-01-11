<?php

namespace App\Filament\Resources\LabelResource\Pages;

use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\LabelResource;

class EditLabel extends EditRecord
{
    protected static string $resource = LabelResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getActions(): array
    {
        return [
            //
        ];
    }
}
