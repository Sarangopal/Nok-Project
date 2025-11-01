<?php

namespace App\Filament\Resources\Offers\Schemas;

use App\Models\Registration;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class OfferForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('title')->required(),
            Textarea::make('body')->columnSpanFull(),
            TextInput::make('promo_code'),
            DatePicker::make('starts_at'),
            DatePicker::make('ends_at'),
            Toggle::make('active')->default(true),
            Select::make('registrations')
                ->label('Assign to Members (Only Approved)')
                ->multiple()
                ->preload()
                ->searchable()
                ->relationship(
                    'registrations',
                    'memberName',
                    fn ($query) => $query->where('renewal_status', 'approved')->orderBy('memberName')
                )
                ->getOptionLabelFromRecordUsing(fn (Registration $record) => $record->memberName . ' (' . ($record->nok_id ?? 'N/A') . ')')
                ->helperText('Select one or more approved members to assign this offer to')
                ->columnSpanFull(),
        ]);
    }
}


