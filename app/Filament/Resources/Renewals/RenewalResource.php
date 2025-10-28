<?php

namespace App\Filament\Resources\Renewals;

use App\Models\Renewal;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use BackedEnum;
use UnitEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RenewalResource extends Resource
{
    protected static ?string $model = Renewal::class;

    // Title for the record
    protected static ?string $recordTitleAttribute = 'memberName';

    // Correct icon assignment
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-user-group';
    protected static string|UnitEnum|null $navigationGroup = 'Memberships';
    protected static ?int $navigationSort = 3;

    public static function form(Schema $schema): Schema
    {
        return \App\Filament\Resources\Renewals\Schemas\RenewalForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return \App\Filament\Resources\Renewals\Tables\RenewalsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\Renewals\Pages\ListRenewals::route('/'),
            'create' => \App\Filament\Resources\Renewals\Pages\CreateRenewal::route('/create'),
            'edit' => \App\Filament\Resources\Renewals\Pages\EditRenewal::route('/{record}/edit'),
        ];
    }

    // Filter to show ONLY approved members who need renewal (expired or expiring within 30 days)
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where(function($query) {
                // Show only approved members (either new or renewed)
                $query->where('login_status', 'approved')
                      ->orWhere('renewal_status', 'approved');
            })
            ->whereNotNull('card_valid_until')
            ->where('card_valid_until', '<=', now()->addDays(30))
            ->orderBy('card_valid_until', 'asc');
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
