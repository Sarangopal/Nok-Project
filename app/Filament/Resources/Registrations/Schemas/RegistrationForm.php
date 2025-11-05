<?php

namespace App\Filament\Resources\Registrations\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class RegistrationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('member_type'),
                TextInput::make('nok_id')
                ->unique(table: 'registrations', column: 'nok_id', ignoreRecord: true)
                ->validationMessages([
                    'unique' => '⚠️ This NOK ID is already registered in the system.',
                ])
                ->helperText('Must be unique - will check for duplicates'),
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
                    ->required()
                    ->unique(table: 'registrations', column: 'email', ignoreRecord: true)
                    ->validationMessages([
                        'unique' => '⚠️ This email is already registered in the system.',
                    ])
                    ->helperText('Must be unique - will check for duplicates'),
                TextInput::make('mobile')
                    ->required()
                    ->unique(table: 'registrations', column: 'mobile', ignoreRecord: true)
                    ->validationMessages([
                        'unique' => '⚠️ This mobile number is already registered in the system.',
                    ])
                    ->helperText('Must be unique - will check for duplicates'),
                TextInput::make('whatsapp')
                    ->unique(table: 'registrations', column: 'whatsapp', ignoreRecord: true)
                    ->validationMessages([
                        'unique' => '⚠️ This WhatsApp number is already registered.',
                    ]),
                TextInput::make('department'),
                TextInput::make('job_title'),
                TextInput::make('institution'),
                TextInput::make('passport')
                    ->unique(table: 'registrations', column: 'passport', ignoreRecord: true)
                    ->validationMessages([
                        'unique' => '⚠️ This passport number is already registered in the system.',
                    ])
                    ->helperText('Must be unique if provided'),
                TextInput::make('civil_id')
                    ->required()
                    ->unique(table: 'registrations', column: 'civil_id', ignoreRecord: true)
                    ->validationMessages([
                        'unique' => '⚠️ This Civil ID is already registered in the system.',
                    ])
                    ->helperText('Must be unique - will check for duplicates'),
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
                DatePicker::make('card_valid_until')->label('Renewal Date')->native(false),
                // FileUpload::make('photo_path')
                //     ->label('Member Photo')
                //     ->image()
                //     ->directory('members/photos')
                //     ->disk('public')
                //     ->imageEditor()
                //     ->columnSpanFull(),
            ]);
    }
}
