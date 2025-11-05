<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RenewalReminderResource\Pages;
use App\Models\RenewalReminder;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use BackedEnum;
use UnitEnum;

class RenewalReminderResource extends Resource
{
    protected static ?string $model = RenewalReminder::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-bell-alert';
    protected static string|UnitEnum|null $navigationGroup = 'Memberships';
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationLabel = 'Renewal Reminders';
    protected static ?string $modelLabel = 'Renewal Reminder';
    protected static ?string $pluralModelLabel = 'Renewal Reminders';

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('member_name')
                    ->label('Member Name')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                    
                TextColumn::make('card_valid_until')
                    ->label('Card Expiry Date')
                    ->date()
                    ->sortable(),
                    
                BadgeColumn::make('days_before_expiry')
                    ->label('Days Before Expiry')
                    ->sortable()
                    ->colors([
                        'danger' => -1,
                        'warning' => 0,
                        'primary' => 1,
                        'success' => fn ($state) => $state >= 7,
                    ])
                    ->formatStateUsing(function ($state) {
                        if ($state === -1) {
                            return 'EXPIRED';
                        } elseif ($state === 0) {
                            return 'TODAY';
                        } else {
                            return "{$state} days";
                        }
                    }),
                    
                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'sent',
                        'danger' => 'failed',
                    ])
                    ->formatStateUsing(fn (string $state): string => ucfirst($state)),
                    
                TextColumn::make('created_at')
                    ->label('Sent At')
                    ->dateTime('M d, Y h:i A')
                    ->sortable(),
                    
                TextColumn::make('error_message')
                    ->label('Error')
                    ->limit(50)
                    ->tooltip(function (TextColumn $column): ?string {
                        $state = $column->getState();
                        return $state ? (string) $state : null;
                    })
                    ->default('—')
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'sent' => 'Sent',
                        'failed' => 'Failed',
                    ]),
                    
                SelectFilter::make('days_before_expiry')
                    ->label('Reminder Interval')
                    ->options([
                        -1 => 'Expired',
                        0 => 'Today',
                        1 => '1 Day',
                        7 => '7 Days',
                        15 => '15 Days',
                        30 => '30 Days',
                    ]),
                    
                Filter::make('sent_today')
                    ->label('Sent Today')
                    ->query(fn (Builder $query): Builder => $query->whereDate('created_at', today())),
                    
                Filter::make('sent_this_week')
                    ->label('Sent This Week')
                    ->query(fn (Builder $query): Builder => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])),
                    
                Filter::make('failed_only')
                    ->label('Failed Only')
                    ->query(fn (Builder $query): Builder => $query->where('status', 'failed')),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc')
            ->poll('60s'); // Auto-refresh every 60 seconds
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRenewalReminders::route('/'),
            'view' => Pages\ViewRenewalReminder::route('/{record}'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $todayCount = static::getModel()::whereDate('created_at', today())->count();
        return $todayCount > 0 ? (string) $todayCount : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    // Remove create and edit capabilities as this is a log table
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }
}

