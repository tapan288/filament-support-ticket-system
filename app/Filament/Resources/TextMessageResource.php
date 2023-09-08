<?php

namespace App\Filament\Resources;

use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\TextMessage;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\TextMessageResource\Pages;

class TextMessageResource extends Resource
{
    protected static ?string $model = TextMessage::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('sent_by')
                    ->relationship('sentBy', 'name')
                    ->placeholder('Message Sent By'),
                Select::make('sent_to')
                    ->relationship('sentTo', 'name'),
                Textarea::make('message'),
                Textarea::make('response_payload'),
                Select::make('status')
                    ->options(TextMessage::STATUS),
                Textarea::make('remarks'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->sortable(),
                TextColumn::make('sentBy.name')
                    ->searchable()
                    ->sortable()
                    ->default('-')
                    ->label('Message Sent By'),
                TextColumn::make('sentTo.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('message')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('remarks')
                    ->default('-')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->sortable()
                    ->date(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(TextMessage::STATUS),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                // Tables\Actions\CreateAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTextMessages::route('/'),
        ];
    }
}
