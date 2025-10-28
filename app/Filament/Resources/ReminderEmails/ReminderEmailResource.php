<?php

namespace App\Filament\Resources\ReminderEmails;

use App\Models\RenewalReminder;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Schemas\Schema;
use BackedEnum;
use UnitEnum;

class ReminderEmailResource extends Resource
{
    protected static ?string $model = RenewalReminder::class;

    protected static ?string $navigationLabel = 'Reminder Emails';
    protected static ?string $pluralModelLabel = 'Reminder Emails';
    protected static ?string $modelLabel = 'Reminder Email';
    protected static ?string $slug = 'reminder-emails';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-envelope';
    protected static string|UnitEnum|null $navigationGroup = 'Memberships';
    protected static ?int $navigationSort = 4;

    public static function form(Schema $schema): Schema
    {
        // View only - no form needed
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return \App\Filament\Resources\ReminderEmails\Tables\ReminderEmailsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\ReminderEmails\Pages\ListReminderEmails::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false; // Read-only resource
    }
}




