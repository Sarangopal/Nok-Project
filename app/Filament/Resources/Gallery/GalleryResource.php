<?php

namespace App\Filament\Resources\Gallery;

use App\Filament\Resources\Gallery\GalleryResource\Pages;
use App\Models\Gallery;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ActionGroup;
use BackedEnum;
use UnitEnum;

class GalleryResource extends Resource
{
    protected static ?string $model = Gallery::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-photo';
    protected static string|UnitEnum|null $navigationGroup = 'Media & Events';
    
    protected static ?int $navigationSort = 4;
    
    protected static ?string $navigationLabel = 'Gallery';

    public static function form(Schema $schema): Schema
    {
        return \App\Filament\Resources\Gallery\Schemas\GalleryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->label('Image')
                    ->circular()
                    ->size(60)
                    ->disk(null)  // Don't use disk resolution, treat as full URL
                    ->getStateUsing(fn($record) => $record->image_url)  // Get full URL from accessor
                    ->defaultImageUrl(url('nokw/assets/img/project/default-placeholder.jpg')),
                
                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->limit(40)
                    ->wrap(),
                
                BadgeColumn::make('category')
                    ->colors([
                        'primary' => 'aaravam',
                        'success' => 'nightingales2024',
                        'info' => 'nightingales2023',
                        'warning' => 'sports',
                        'danger' => 'others',
                    ])
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('year')
                    ->sortable()
                    ->alignCenter(),
                
                TextColumn::make('display_order')
                    ->label('Order')
                    ->sortable()
                    ->alignCenter(),
                
                BooleanColumn::make('is_published')
                    ->label('Published')
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('display_order', 'asc')
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    EditAction::make(),
                    DeleteAction::make(),
                ]),
            ])
            
            
        
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGalleries::route('/'),
            'create' => Pages\CreateGallery::route('/create'),
            'edit' => Pages\EditGallery::route('/{record}/edit'),
        ];
    }
}
