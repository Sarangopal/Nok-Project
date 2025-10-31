<?php

namespace App\Filament\Resources\Gallery\GalleryResource\Pages;

use App\Filament\Resources\Gallery\GalleryResource;
use App\Models\Gallery;
use App\Services\ImageOptimizer;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class CreateGallery extends CreateRecord
{
    protected static string $resource = GalleryResource::class;
    
    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    /**
     * Handle creation with auto image optimization
     */
    protected function handleRecordCreation(array $data): Model
    {
        // Optimize image on disk before saving record
        if (!empty($data['image']) && is_string($data['image'])) {
            $this->optimizeUploadedImage($data['image']);
        }

        return parent::handleRecordCreation($data);
    }

    protected function optimizeUploadedImage(string $relativePath): void
    {
        $disk = 'public';
        $fullPath = Storage::disk($disk)->path($relativePath);

        if (file_exists($fullPath)) {
            try {
                ImageOptimizer::optimize($fullPath);
            } catch (\Throwable $e) {
                // Silently continue; do not block creation if optimization fails
            }
        }
    }
}




