<?php

namespace App\Filament\Resources\ApprovedRenewals\Pages;

use App\Filament\Resources\ApprovedRenewals\ApprovedRenewalResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewApprovedRenewal extends ViewRecord
{
    protected static string $resource = ApprovedRenewalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}

