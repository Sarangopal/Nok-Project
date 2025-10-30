<?php

namespace App\Filament\Resources\Gallery\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class GalleryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->maxLength(255)
                    ->placeholder('Enter gallery item title')
                    ->columnSpanFull(),
                
                Textarea::make('description')
                    ->rows(3)
                    ->maxLength(500)
                    ->placeholder('Optional description for this image')
                    ->columnSpanFull(),
                
                FileUpload::make('images')
                    ->label('Images')
                    ->image()
                    ->multiple()
                    ->reorderable()
                    ->disk('public')  // Use public disk so files are accessible via storage link
                    ->directory('gallery')
                    ->maxSize(5120) // 5MB max
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/jpg'])
                    ->helperText('Upload one or many images (max 5MB each). Recommended: 1200x800px or higher')
                    ->columnSpanFull(),
                
                Select::make('category')
                    ->required()
                    ->options([
                        'aaravam' => 'Aaravam',
                        'nightingales2024' => 'Nightingales 2024',
                        'nightingales2023' => 'Nightingales 2023',
                        'sports' => 'Sports Events',
                        'cultural' => 'Cultural Events',
                        'others' => 'Others',
                    ])
                    ->default('others')
                    ->searchable(),
                
                TextInput::make('year')
                    ->required()
                    ->numeric()
                    ->default(date('Y'))
                    ->minValue(2000)
                    ->maxValue(2100)
                    ->helperText('Year of the event'),
                
                TextInput::make('display_order')
                    ->numeric()
                    ->default(0)
                    ->helperText('Lower numbers appear first (0 = highest priority)'),
                
                Toggle::make('is_published')
                    ->label('Published')
                    ->default(true)
                    ->helperText('Show this image on the gallery page'),
            ]);
    }
}

