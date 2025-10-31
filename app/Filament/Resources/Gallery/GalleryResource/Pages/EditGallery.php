<?php

namespace App\Filament\Resources\Gallery\GalleryResource\Pages;

use App\Filament\Resources\Gallery\GalleryResource;
use App\Services\ImageOptimizer;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class EditGallery extends EditRecord
{
    protected static string $resource = GalleryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Mutate form data before saving (edit mode) - optimize uploaded image
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Optimize the uploaded image if it's new/changed
        if (!empty($data['image']) && is_string($data['image'])) {
            $this->optimizeUploadedImage($data['image']);
        }
        
        return $data;
    }

    protected function optimizeUploadedImage(string $relativePath): void
    {
        $disk = 'public';
        $fullPath = Storage::disk($disk)->path($relativePath);

        if (file_exists($fullPath)) {
            try {
                ImageOptimizer::optimize($fullPath);
            } catch (\Throwable $e) {
                // Silently continue
            }
        }
    }
}




