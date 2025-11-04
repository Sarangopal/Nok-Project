<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Schemas\Schema;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            // Event Details
            TextInput::make('title')
                ->required()
                ->maxLength(255)
                ->reactive()
                ->afterStateUpdated(fn ($state, callable $set) => $set('slug', \Illuminate\Support\Str::slug($state)))
                ->columnSpanFull(),
            
            TextInput::make('slug')
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true)
                ->helperText('Auto-generated from title, but can be customized')
                ->columnSpanFull(),
            
            Textarea::make('description')
                ->maxLength(500)
                ->rows(3)
                ->helperText('Short description for event listing (max 500 chars)')
                ->columnSpanFull(),
            
            RichEditor::make('body')
                ->required()
                ->toolbarButtons([
                    'bold',
                    'italic',
                    'underline',
                    'strike',
                    'link',
                    'bulletList',
                    'orderedList',
                    'blockquote',
                    'codeBlock',
                    'undo',
                    'redo',
                ])
                ->helperText('Full event content with rich formatting')
                ->columnSpanFull(),

            // Event Schedule & Location
            DatePicker::make('event_date')
                ->required()
                ->native(false)
                ->displayFormat('d/m/Y')
                ->helperText('Date of the event'),
            
            TextInput::make('event_time')
                ->maxLength(50)
                ->placeholder('e.g., 6:00 PM - 9:00 PM')
                ->helperText('Event time or time range'),
            
            TextInput::make('location')
                ->maxLength(255)
                ->placeholder('e.g., NOK Community Hall, Kuwait')
                ->columnSpanFull(),

            // Media & Categorization
            FileUpload::make('banner_image')
                ->image()
                ->disk('public')
                ->directory('events/banners')
                ->imageEditor()
                ->imageEditorAspectRatios([
                    '16:9',
                    '4:3',
                    '1:1',
                ])
                ->maxSize(2048)
                ->helperText('Upload event banner image (max 2MB)')
                ->columnSpanFull(),
            
            Select::make('category')
                ->options([
                    'Cultural' => 'Cultural',
                    'Educational' => 'Educational',
                    'Social' => 'Social',
                    'Charity' => 'Charity',
                    'Sports' => 'Sports',
                    'Meeting' => 'Meeting',
                    'Workshop' => 'Workshop',
                    'Other' => 'Other',
                ])
                ->searchable()
                ->placeholder('Select category'),

            // Publishing Options
            Toggle::make('is_published')
                ->label('Published')
                ->helperText('Make this event visible on the website')
                ->default(false),
            
            Toggle::make('featured')
                ->label('Featured Event')
                ->helperText('Show on homepage and highlighted sections')
                ->default(false),
            
            TextInput::make('display_order')
                ->label('Display Order')
                ->numeric()
                ->default(0)
                ->unique(table: 'events', column: 'display_order', ignoreRecord: true)
                ->helperText('Lower numbers appear first. Each event must have a unique order number.')
                ->minValue(0)
                ->maxValue(9999)
                ->validationMessages([
                    'unique' => 'This order number is already in use by another event. Please choose a different number.',
                ]),
            
            TextInput::make('meta_description')
                ->maxLength(160)
                ->helperText('SEO meta description (max 160 chars)')
                ->columnSpanFull(),
        ]);
    }
}

