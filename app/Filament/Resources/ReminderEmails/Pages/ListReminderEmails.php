<?php

namespace App\Filament\Resources\ReminderEmails\Pages;

use App\Filament\Resources\ReminderEmails\ReminderEmailResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Notifications\Notification;

class ListReminderEmails extends ListRecords
{
    protected static string $resource = ReminderEmailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('send_reminders')
                ->label('Send Reminders Now')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Send Renewal Reminders')
                ->modalDescription('This will send reminder emails to all members whose cards are expiring in 30, 15, 7, 1, or 0 days. Are you sure?')
                ->modalSubmitActionLabel('Yes, Send Reminders')
                ->action(function () {
                    try {
                        \Artisan::call('members:send-renewal-reminders');
                        $output = \Artisan::output();
                        
                        Notification::make()
                            ->title('Reminders Sent Successfully')
                            ->body($output)
                            ->success()
                            ->send();
                    } catch (\Exception $e) {
                        Notification::make()
                            ->title('Error Sending Reminders')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Actions\Action::make('stats')
                ->label('Statistics')
                ->icon('heroicon-o-chart-bar')
                ->color('info')
                ->modalHeading('Reminder Email Statistics')
                ->modalWidth('2xl')
                ->modalContent(fn () => view('filament.modals.reminder-stats'))
                ->modalSubmitAction(false)
                ->modalCancelActionLabel('Close'),
        ];
    }
}




