<?php

namespace App\Filament\Member\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
use Filament\Schemas\Schema;
use Filament\Auth\Pages\Login as BaseLogin;
use Illuminate\Validation\ValidationException;

class Login extends BaseLogin
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('civil_id')
                    ->label('Civil ID')
                    ->placeholder('Enter your Kuwait Civil ID')
                    ->required()
                    ->autocomplete()
                    ->autofocus()
                    ->extraInputAttributes(['tabindex' => 1])
                    ->helperText('Use the Civil ID you registered with'),
                
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required()
                    ->extraInputAttributes(['tabindex' => 2])
                    ->helperText('Password sent to your email when approved'),
                
                Checkbox::make('remember')
                    ->label('Remember me')
                    ->extraInputAttributes(['tabindex' => 3]),
            ])
            ->statePath('data');
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'civil_id' => $data['civil_id'],
            'password' => $data['password'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.civil_id' => __('These credentials do not match our records or your membership is not approved yet.'),
        ]);
    }
}
