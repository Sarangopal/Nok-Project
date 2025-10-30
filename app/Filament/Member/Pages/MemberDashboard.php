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
            // \App\Filament\Member\Widgets\RenewalRequestWidget::class,  // Hidden per request
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
                ->requiresConfirmation()
                ->modalHeading('Reset your password?')
                ->modalDescription('A new secure password will be generated and emailed to you.')
                ->action(function () {
                    $member = Auth::guard('members')->user();
                    if (!$member) {
                        return;
                    }

                    $newPassword = Str::password(12);
                    $member->password = $newPassword;
                    $member->save();

                    try {
                        $mailData = ['record' => $member, 'password' => $newPassword];
                        Mail::to($member->email)->send(new MembershipCardMail($mailData));

                        Notification::make()
                            ->title('A new password was emailed to you')
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Password updated, but email failed')
                            ->body('Error: ' . $e->getMessage())
                            ->warning()
                            ->send();
                    }
                }),
        ];
    }
}

