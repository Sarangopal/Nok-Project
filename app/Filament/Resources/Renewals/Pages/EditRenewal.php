<?php

namespace App\Filament\Resources\Renewals\Pages;

use App\Filament\Resources\Renewals\RenewalResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditRenewal extends EditRecord
{
    protected static string $resource = RenewalResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
