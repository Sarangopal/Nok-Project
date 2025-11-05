<?php

namespace App\Filament\Resources\RenewalReminderResource\Pages;

use App\Filament\Resources\RenewalReminderResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions\Action;

class ListRenewalReminders extends ListRecords
{
    protected static string $resource = RenewalReminderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('run_reminders')
                ->label('Send Reminders Now')
                ->icon('heroicon-o-paper-airplane')
                ->color('success')
                ->requiresConfirmation()
                ->modalHeading('Send Renewal Reminders')
                ->modalDescription('This will run the renewal reminder command now. Are you sure?')
                ->action(function () {
                    \Illuminate\Support\Facades\Artisan::call('members:send-renewal-reminders');
                    \Filament\Notifications\Notification::make()
                        ->title('Reminders Sent')
                        ->body('Renewal reminder command has been executed successfully.')
                        ->success()
                        ->send();
                }),
        ];
    }
}

