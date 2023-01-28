<?php

namespace App\Filament\Resources;

use App\Models\Role;
use App\Models\User;
use Filament\Tables;
use App\Models\Ticket;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextInputColumn;
use App\Filament\Resources\TicketResource\Pages;
use App\Filament\Resources\TicketResource\RelationManagers\LabelsRelationManager;
use App\Filament\Resources\TicketResource\RelationManagers\CategoriesRelationManager;

class TicketResource extends Resource
{
    protected static ?string $model = Ticket::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    protected function getDefaultTableSortColumn(): ?string
    {
        return 'created_at';
    }

    protected function getDefaultTableSortDirection(): ?string
    {
        return 'desc';
    }

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->schema([
                TextInput::make('title')
                    ->required(),
                Select::make('priority')
                    ->options(self::$model::PRIORITY)
                    ->required()
                    ->in(self::$model::PRIORITY),
                Select::make('assigned_to')
                    ->options(
                        User::whereHas('roles', function (Builder $query) {
                            $query->where('name', Role::ROLES['Agent']);
                        })
                            ->get()
                            ->pluck('name', 'id')
                            ->toArray()
                    )
                    ->required(),
                Textarea::make('description'),
                Textarea::make('comment'),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->sortable()
                    ->description(fn (Ticket $record): string => $record?->description ?? ''),
                SelectColumn::make('status')
                    ->disabled(!auth()->user()->hasPermission('ticket_edit'))
                    ->disablePlaceholderSelection()
                    ->sortable()
                    ->options(self::$model::STATUS),
                BadgeColumn::make('priority')
                    ->sortable()
                    ->colors([
                        'warning' => self::$model::PRIORITY['Medium'],
                        'success' => self::$model::PRIORITY['Low'],
                        'danger' => self::$model::PRIORITY['High'],
                    ])
                    ->enum(self::$model::PRIORITY),
                TextColumn::make('assignedTo.name'),
                TextColumn::make('assignedBy.name'),
                TextInputColumn::make('comment')
                    ->disabled(!auth()->user()->hasPermission('ticket_edit')),
                TextColumn::make('created_at')
                    ->sortable()
                    ->dateTime(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(self::$model::STATUS),
                SelectFilter::make('priority')
                    ->options(self::$model::PRIORITY)
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            CategoriesRelationManager::class,
            LabelsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTickets::route('/'),
            'create' => Pages\CreateTicket::route('/create'),
            'edit' => Pages\EditTicket::route('/{record}/edit'),
            'view' => Pages\ViewTicket::route('/{record}'),
        ];
    }
}
