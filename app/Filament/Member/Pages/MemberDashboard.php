<?php

namespace App\Filament\Member\Pages;

use App\Mail\MembershipCardMail;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\Widget;

class MemberDashboard extends BaseDashboard
{
    protected static string $routePath = '/';
    
    protected static ?string $title = 'My Dashboard';
    
    public function getWidgets(): array
    {
        return [
            \App\Filament\Member\Widgets\MemberStatsWidget::class,
            \App\Filament\Member\Widgets\MemberProfileTableWidget::class,
            \App\Filament\Member\Widgets\RenewalRequestWidget::class,  // Re-enabled for renewal testing
            \App\Filament\Member\Widgets\MemberCardWidget::class,
            // \App\Filament\Member\Widgets\MemberOffersListWidget::class,  // Hidden per request
        ];
    }
    
    public function getColumns(): int | array
    {
        return [
            'default' => 1,
            'sm' => 1,
            'md' => 2,
            'lg' => 2,
            'xl' => 2,
            '2xl' => 2,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('reset_password')
                ->label('Reset Password')
                ->icon('heroicon-o-key')
                ->color('warning')
                ->modalHeading('Change Your Password')
                ->modalDescription('Enter your current password and choose a new password.')
                ->form([
                    \Filament\Forms\Components\TextInput::make('current_password')
                        ->label('Current Password')
                        ->password()
                        ->required()
                        ->rules(['required']),
                    
                    \Filament\Forms\Components\TextInput::make('new_password')
                        ->label('New Password')
                        ->password()
                        ->required()
                        ->minLength(8)
                        ->rules(['required', 'min:8'])
                        ->helperText('Minimum 8 characters'),
                    
                    \Filament\Forms\Components\TextInput::make('new_password_confirmation')
                        ->label('Confirm New Password')
                        ->password()
                        ->required()
                        ->same('new_password')
                        ->rules(['required'])
                        ->helperText('Must match new password'),
                ])
                ->action(function (array $data) {
                    $member = Auth::guard('members')->user();
                    if (!$member) {
                        return;
                    }

                    // Verify current password
                    if (!\Illuminate\Support\Facades\Hash::check($data['current_password'], $member->password)) {
                        Notification::make()
                            ->title('Current password is incorrect')
                            ->danger()
                            ->send();
                        return;
                    }

                    // Update password
                    $member->password = $data['new_password'];
                    $member->save();

                    Notification::make()
                        ->title('Password changed successfully')
                        ->success()
                        ->send();
                }),
        ];
    }
}

