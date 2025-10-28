<?php

namespace App\Filament\Resources\Gallery\GalleryResource\Pages;

use App\Filament\Resources\Gallery\GalleryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateGallery extends CreateRecord
{
    protected static string $resource = GalleryResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}




