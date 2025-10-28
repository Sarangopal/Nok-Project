<?php

namespace App\Filament\Resources\Renewals\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RenewalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('member_type')
                    ->options(['new' => 'New', 'existing' => 'Existing'])
                    ->default('new')
                    ->required(),
                TextInput::make('nok_id'),
                DatePicker::make('doj'),
                TextInput::make('memberName')
                    ->required(),
                TextInput::make('age')
                    ->required()
                    ->numeric(),
                TextInput::make('gender')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('mobile')
                    ->required(),
                TextInput::make('whatsapp'),
                TextInput::make('department'),
                TextInput::make('job_title'),
                TextInput::make('institution'),
                TextInput::make('passport'),
                TextInput::make('civil_id'),
                TextInput::make('blood_group'),
                Textarea::make('address')
                    ->columnSpanFull(),
                TextInput::make('phone_india')
                    ->tel(),
                TextInput::make('nominee_name'),
                TextInput::make('nominee_relation'),
                TextInput::make('nominee_contact'),
                TextInput::make('guardian_name'),
                TextInput::make('guardian_contact'),
                TextInput::make('bank_account_name'),
                TextInput::make('account_number'),
                TextInput::make('ifsc_code'),
                TextInput::make('bank_branch'),
            ]);
    }
}
