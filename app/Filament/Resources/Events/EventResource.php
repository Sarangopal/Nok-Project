<?php

namespace App\Filament\Resources\Events;

use App\Models\Event;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use BackedEnum;
use UnitEnum;

class EventResource extends Resource
{
    protected static ?string $model = Event::class;
    protected static ?string $navigationLabel = 'Events';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-calendar';
    protected static string|UnitEnum|null $navigationGroup = 'Media & Events';

    protected static ?int $navigationSort = 5;

    public static function form(Schema $schema): Schema
    {
        return \App\Filament\Resources\Events\Schemas\EventForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('banner_image')
                ->label('Banner')
                ->disk(null)  // Don't use disk resolution, treat as full URL
                ->getStateUsing(function ($record) {
                    if ($record->banner_image) {
                        return asset('storage/' . $record->banner_image);
                    }
                    return null;
                })
                ->width(100)
                ->height(60),
            TextColumn::make('title')
                ->searchable()
                ->sortable()
                ->limit(50),
            TextColumn::make('event_date')
                ->date('d M Y')
                ->sortable(),
            TextColumn::make('event_time')
                ->sortable(),
            TextColumn::make('location')
                ->limit(30)
                ->toggleable(),
            TextColumn::make('category')
                ->badge()
                ->toggleable(),
            TextColumn::make('display_order')
                ->label('Order')
                ->sortable()
                ->alignCenter()
                ->badge()
                ->color('info'),
            BooleanColumn::make('is_published')
                ->label('Published')
                ->sortable(),
            BooleanColumn::make('featured')
                ->label('Featured')
                ->sortable(),
            TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->actions([
            EditAction::make(),
            DeleteAction::make(),
        ])
        ->bulkActions([
            DeleteBulkAction::make(),
        ])
        ->defaultSort('display_order', 'asc');
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\Events\Pages\ListEvents::route('/'),
            'create' => \App\Filament\Resources\Events\Pages\CreateEvent::route('/create'),
            'edit' => \App\Filament\Resources\Events\Pages\EditEvent::route('/{record}/edit'),
        ];
    }
}

