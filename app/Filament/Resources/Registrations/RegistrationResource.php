<?php

namespace App\Filament\Resources\Registrations;

use App\Filament\Resources\Registrations\Pages\CreateRegistration;
use App\Filament\Resources\Registrations\Pages\EditRegistration;
use App\Filament\Resources\Registrations\Pages\ListRegistrations;
use App\Filament\Resources\Registrations\Schemas\RegistrationForm;
use App\Filament\Resources\Registrations\Tables\RegistrationsTable;
use App\Models\Registration;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables;

class RegistrationResource extends Resource
{
    protected static ?string $model = Registration::class;

            // Change the label in the navigation
    protected static ?string $navigationLabel = 'New Registrations'; 
        // Change the title on the index page and breadcrumbs
    protected static ?string $pluralModelLabel = 'New Registrations'; 
        // Change the plural label in the navigation
    protected static ?string $modelLabel = 'Registration';
    

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-plus';
    protected static string|UnitEnum|null $navigationGroup = 'Memberships';
    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return RegistrationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RegistrationsTable::configure($table);
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
            'index' => ListRegistrations::route('/'),
            'create' => CreateRegistration::route('/create'),
            'edit' => EditRegistration::route('/{record}/edit'),
        ];
    }
}
