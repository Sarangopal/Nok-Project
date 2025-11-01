<?php

namespace App\Filament\Resources\RenewalRequests\Pages;

use App\Filament\Resources\RenewalRequests\RenewalRequestResource;
use Filament\Resources\Pages\ListRecords;

class ListRenewalRequests extends ListRecords
{
    protected static string $resource = RenewalRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // No create button - members create requests from their dashboard
        ];
    }
}

