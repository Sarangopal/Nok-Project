<?php

namespace App\Filament\Resources\ApprovedRenewals;

use App\Models\Registration;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use BackedEnum;
use UnitEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ApprovedRenewalResource extends Resource
{
    protected static ?string $model = Registration::class;

    // Navigation settings
    protected static ?string $navigationLabel = 'Approved Renewals'; 
    protected static ?string $pluralModelLabel = 'Approved Renewals'; 
    protected static ?string $modelLabel = 'Approved Renewal';
    
    // Title for the record
    protected static ?string $recordTitleAttribute = 'memberName';

    // Icon
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-check-badge';
    protected static string|UnitEnum|null $navigationGroup = 'Memberships';

    // Navigation sort order
    protected static ?int $navigationSort = 4;

    // Show badge with count
    public static function getNavigationBadge(): ?string
    {
        $count = static::getModel()::where('renewal_status', 'approved')
            ->whereNotNull('last_renewed_at')
            ->count();
        return $count > 0 ? (string)$count : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    // Filter to only show approved renewals (members who have been renewed)
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('renewal_status', 'approved')
            ->whereNotNull('last_renewed_at')  // Only show members who have actually been renewed
            ->orderBy('last_renewed_at', 'desc');  // Most recent renewals first
    }

    public static function form(Schema $schema): Schema
    {
        return \App\Filament\Resources\Renewals\Schemas\RenewalForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return \App\Filament\Resources\ApprovedRenewals\Tables\ApprovedRenewalsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\ApprovedRenewals\Pages\ListApprovedRenewals::route('/'),
            'view' => \App\Filament\Resources\ApprovedRenewals\Pages\ViewApprovedRenewal::route('/{record}'),
            'edit' => \App\Filament\Resources\ApprovedRenewals\Pages\EditApprovedRenewal::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}

