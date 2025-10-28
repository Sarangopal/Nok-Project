<?php

namespace App\Filament\Resources\ApprovedRenewals\Pages;

use App\Filament\Resources\ApprovedRenewals\ApprovedRenewalResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditApprovedRenewal extends EditRecord
{
    protected static string $resource = ApprovedRenewalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Renewal Updated')
            ->body('The renewal record has been updated successfully.');
    }
}

