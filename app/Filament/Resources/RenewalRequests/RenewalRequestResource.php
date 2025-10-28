<?php

namespace App\Filament\Resources\RenewalRequests;

use App\Models\Registration;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use BackedEnum;
use UnitEnum;
use Illuminate\Database\Eloquent\Builder;

class RenewalRequestResource extends Resource
{
    protected static ?string $model = Registration::class;

    // Change the label in the navigation
    protected static ?string $navigationLabel = 'Renewal Requests'; 
    // Change the title on the index page
    protected static ?string $pluralModelLabel = 'Renewal Requests'; 
    protected static ?string $modelLabel = 'Renewal Request';

    // Title for the record
    protected static ?string $recordTitleAttribute = 'memberName';

    // Icon
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-arrow-path';
    protected static string|UnitEnum|null $navigationGroup = 'Memberships';

    // Navigation sort order
    protected static ?int $navigationSort = 2;

    // Show badge with pending renewal request count
    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::whereNotNull('renewal_requested_at')
            ->where('renewal_status', 'pending')
            ->count();
        return $count > 0 ? (string)$count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    // Filter to only show member-requested renewals
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->whereNotNull('renewal_requested_at')
            ->orderBy('renewal_requested_at', 'desc');
    }

    public static function form(Schema $schema): Schema
    {
        return \App\Filament\Resources\Renewals\Schemas\RenewalForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return \App\Filament\Resources\RenewalRequests\Tables\RenewalRequestsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRenewalRequests::route('/'),
            'edit' => Pages\EditRenewalRequest::route('/{record}/edit'),
        ];
    }
}

