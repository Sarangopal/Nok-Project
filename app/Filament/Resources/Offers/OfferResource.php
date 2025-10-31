<?php

namespace App\Filament\Resources\Offers;

use App\Models\Offer;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Filament\Resources\Offers\Schemas\OfferForm;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use BackedEnum;
use UnitEnum;

class OfferResource extends Resource
{
    protected static ?string $model = Offer::class;
    protected static ?string $navigationLabel = 'Offers & Discounts';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-gift';
    protected static string|UnitEnum|null $navigationGroup = null; // Hide from navigation
    protected static ?int $navigationSort = 6;
    protected static bool $shouldRegisterNavigation = false; // Hide from sidebar


    public static function form(Schema $schema): Schema
    {
        return OfferForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('title')->searchable()->sortable(),
            TextColumn::make('promo_code')->searchable(),
            TextColumn::make('registrations_count')
                ->label('Assigned Members')
                ->counts('registrations')
                ->badge()
                ->color('success'),
            TextColumn::make('starts_at')->date()->sortable(),
            TextColumn::make('ends_at')->date()->sortable(),
            BooleanColumn::make('active')->sortable(),
            TextColumn::make('created_at')->dateTime()->sortable()->toggleable(isToggledHiddenByDefault: true),
        ])->actions([
            
            ActionGroup::make([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ]),
        ])->bulkActions([
            DeleteBulkAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOffers::route('/'),
            'create' => Pages\CreateOffer::route('/create'),
            'edit' => Pages\EditOffer::route('/{record}/edit'),
        ];
    }
}


