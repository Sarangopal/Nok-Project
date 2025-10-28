<?php

namespace App\Filament\Resources\ContactMessages\Pages;

use App\Filament\Resources\ContactMessages\ContactMessageResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListContactMessages extends ListRecords
{
    protected static string $resource = ContactMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //  \Filament\Actions\CreateAction::make()
            // ->label('Add Message'), // <-- Custom name
            // CreateAction::make(),
        ];
    }
}
