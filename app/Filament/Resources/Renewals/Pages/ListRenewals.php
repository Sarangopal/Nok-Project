<?php

namespace App\Filament\Resources\Renewals\Pages;

use App\Filament\Resources\Renewals\RenewalResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRenewals extends ListRecords
{
    protected static string $resource = RenewalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // CreateAction::make(), // Hidden - Renewals are managed automatically
        ];
    }
}
