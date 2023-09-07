<?php

namespace App\Filament\Resources\UserResource\Pages;

use Filament\Pages\Actions;
use Illuminate\Support\Facades\Hash;
use App\Filament\Resources\UserResource;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('updaePassword')
                ->label('Update Password')
                ->form([
                    TextInput::make('password')
                        ->password()
                        ->confirmed()
                        ->disableAutocomplete(),
                    TextInput::make('password_confirmation')
                        ->password()
                        ->disableAutocomplete(),
                ])
                ->action(function (array $data) {
                    $this->record->update([
                        'password' => Hash::make($data['password']),
                    ]);

                    Notification::make()
                        ->success()
                        ->title('Password Updated Successfully')
                        ->send();
                }),
            Actions\DeleteAction::make(),
        ];
    }
}
