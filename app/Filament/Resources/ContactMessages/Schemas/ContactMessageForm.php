<?php

namespace App\Filament\Resources\ContactMessages\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ContactMessageForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('subject')
                    ->required(),
                Textarea::make('message')
                    ->required()
                    ->columnSpanFull(),

                // // 🔹 Add a dropdown for status
                // Select::make('status')
                //     ->label('Status')
                //     ->options([
                //         'pending' => 'Pending',
                //         'done' => 'Done',
                //     ])
                //     ->default('pending')
                //     ->required(),
            ]);
    }
}
