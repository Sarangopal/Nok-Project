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
     * Mutate form data before filling the form (edit mode)
     * Convert single 'image' to 'images' array for the FileUpload field
     */
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Convert single image to array for the multiple upload field
        if (!empty($data['image'])) {
            $data['images'] = [$data['image']];
        }
        
        return $data;
    }

    /**
     * Mutate form data before saving (edit mode)
     * Convert 'images' array back to single 'image' for database
     */
    protected function mutateFormDataBeforeSave(array $data): array
    {
        // On edit, only save the first image to the 'image' column
        if (!empty($data['images']) && is_array($data['images'])) {
            $data['image'] = $data['images'][0];
            
            // Optimize the uploaded image if it's new
            if (is_string($data['image'])) {
                $this->optimizeUploadedImage($data['image']);
            }
        }
        
        // Remove images field since DB expects 'image'
        unset($data['images']);
        
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




