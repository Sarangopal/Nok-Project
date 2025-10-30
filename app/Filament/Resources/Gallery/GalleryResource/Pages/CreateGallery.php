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
     * Handle creation of multiple gallery records when multiple images are uploaded.
     */
    protected function handleRecordCreation(array $data): Model
    {
        $images = Arr::pull($data, 'images', []);

        // Fallback for single image field if present in old forms
        if (empty($images) && isset($data['image'])) {
            $images = [$data['image']];
            unset($data['image']);
        }

        $last = null;

        foreach ($images as $path) {
            // Optimize image on disk before saving record
            if (is_string($path)) {
                $this->optimizeUploadedImage($path);
            }

            $recordData = $data;
            $recordData['image'] = $path;
            $last = Gallery::create($recordData);
        }

        // Return the last created model to satisfy the contract
        return $last ?? Gallery::create($data);
    }

    protected function optimizeUploadedImage(string $relativePath): void
    {
        $disk = 'public';
        $fullPath = Storage::disk($disk)->path($relativePath);

        try {
            ImageOptimizer::optimize($fullPath);
        } catch (\Throwable $e) {
            // Silently continue; do not block creation if optimization fails
        }
    }
}




