<?php

namespace App\Filament\Resources\Gallery\GalleryResource\Pages;

use App\Filament\Resources\Gallery\GalleryResource;
use App\Models\Gallery;
use App\Services\ImageOptimizer;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class ListGalleries extends ListRecords
{
    protected static string $resource = GalleryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('bulk_upload')
                ->label('Bulk Upload')
                ->icon('heroicon-o-photo')
                ->color('success')
                ->modalHeading('Bulk Upload Gallery Images')
                ->modalDescription('Upload multiple images at once. Each image will create a separate gallery record.')
                ->form([
                    Forms\Components\TextInput::make('title')
                        ->label('Title (applies to all)')
                        ->maxLength(255)
                        ->placeholder('e.g., Event Name 2025')
                        ->columnSpanFull(),
                    
                    Forms\Components\Textarea::make('description')
                        ->label('Description (applies to all)')
                        ->rows(2)
                        ->maxLength(500)
                        ->columnSpanFull(),
                    
                    Forms\Components\FileUpload::make('images')
                        ->label('Images')
                        ->image()
                        ->multiple()
                        ->reorderable()
                        ->required()
                        ->disk('public')
                        ->directory('gallery')
                        ->maxSize(5120)
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg'])
                        ->helperText('Upload multiple images (max 5MB each)')
                        ->columnSpanFull(),
                    
                    Forms\Components\Select::make('category')
                        ->label('Category')
                        ->required()
                        ->options([
                            'aaravam' => 'Aaravam',
                            'nightingales2024' => 'Nightingales 2024',
                            'nightingales2023' => 'Nightingales 2023',
                            'sports' => 'Sports Events',
                            'cultural' => 'Cultural Events',
                            'others' => 'Others',
                        ])
                        ->default('others'),
                    
                    Forms\Components\TextInput::make('year')
                        ->label('Year')
                        ->required()
                        ->numeric()
                        ->default(date('Y'))
                        ->minValue(2000)
                        ->maxValue(2100),
                    
                    Forms\Components\TextInput::make('display_order')
                        ->label('Starting Display Order')
                        ->numeric()
                        ->default(0)
                        ->helperText('Auto-increments for each image'),
                    
                    Forms\Components\Toggle::make('is_published')
                        ->label('Published')
                        ->default(true),
                ])
                ->action(function (array $data) {
                    $images = Arr::pull($data, 'images', []);

                    if (empty($images)) {
                        Notification::make()
                            ->title('No images selected')
                            ->danger()
                            ->send();
                        return;
                    }

                    $created = 0;
                    $displayOrder = (int) ($data['display_order'] ?? 0);

                    foreach ($images as $index => $imagePath) {
                        // Optimize image
                        if (is_string($imagePath)) {
                            $this->optimizeUploadedImage($imagePath);
                        }

                        $recordData = $data;
                        $recordData['image'] = $imagePath;
                        $recordData['display_order'] = $displayOrder + $index;
                        
                        Gallery::create($recordData);
                        $created++;
                    }

                    Notification::make()
                        ->title("Created {$created} gallery items successfully")
                        ->success()
                        ->send();
                }),
        ];
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




