<?php

namespace App\Filament\Resources\ApprovedRenewals\Pages;

use App\Filament\Resources\ApprovedRenewals\ApprovedRenewalResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListApprovedRenewals extends ListRecords
{
    protected static string $resource = ApprovedRenewalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create action - renewals are created through renewal request process
        ];
    }
    
    public function getTitle(): string
    {
        return 'Approved Renewals';
    }
    
    protected function getHeaderWidgets(): array
    {
        return [
            // Can add widgets here if needed
        ];
    }
}





