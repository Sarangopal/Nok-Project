<?php

namespace App\Filament\Resources\ApprovedRenewals\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\ViewAction;
use Filament\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;

class ApprovedRenewalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->persistFiltersInSession()
            ->deferFilters()
            ->columns([
                TextColumn::make('nok_id')
                    ->label('NOK ID')
                    ->searchable()
                    ->sortable()
                    ->copyable(),
                
                TextColumn::make('memberName')
                    ->label('Member Name')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->email),
                
                TextColumn::make('mobile')
                    ->label('Mobile')
                    ->searchable()
                    ->copyable()
                    ->toggleable(),
                
                TextColumn::make('department')
                    ->label('Department')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('institution')
                    ->label('Institution')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->institution)
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('last_renewed_at')
                    ->label('Renewed On')
                    ->date('d M Y')
                    ->sortable()
                    ->description(function ($record) {
                        if (!$record->last_renewed_at) return null;
                        // Cast to integer to remove decimals
                        // Use absolute value since we're looking back in time
                        $days = (int) abs(now()->diffInDays($record->last_renewed_at, false));
                        if ($days == 0) return 'Today';
                        if ($days == 1) return 'Yesterday';
                        if ($days < 30) return "{$days} days ago";
                        if ($days < 365) {
                            $months = (int) round($days/30);
                            return $months . ' month' . ($months > 1 ? 's' : '') . ' ago';
                        }
                        $years = (int) round($days/365);
                        return $years . ' year' . ($years > 1 ? 's' : '') . ' ago';
                    })
                    ->color('success'),
                
                TextColumn::make('renewal_count')
                    ->label('Renewal Count')
                    ->numeric()
                    ->sortable()
                    ->badge()
                    ->color(fn ($state) => match(true) {
                        $state >= 5 => 'success',
                        $state >= 3 => 'info',
                        $state >= 1 => 'warning',
                        default => 'gray'
                    }),
                
                TextColumn::make('card_valid_until')
                    ->label('Valid Until')
                    ->date('d M Y')
                    ->sortable()
                    ->description(function ($record) {
                        if (!$record->card_valid_until) return null;
                        $days = (int) now()->diffInDays($record->card_valid_until, false);
                        if ($days < 0) return '🔴 Expired';
                        if ($days <= 30) return "⚠️ Expiring in {$days} days";
                        if ($days <= 365) return "✓ Valid for " . round($days/30) . " months";
                        return "✓ Valid for " . round($days/365) . " years";
                    })
                    ->color(function ($record) {
                        if (!$record->card_valid_until) return null;
                        $days = (int) now()->diffInDays($record->card_valid_until, false);
                        if ($days < 0) return 'danger';
                        if ($days <= 30) return 'warning';
                        return 'success';
                    }),
                
                BadgeColumn::make('renewal_status')
                    ->label('Status')
                    ->colors([
                        'success' => 'approved',
                        'warning' => 'pending',
                        'danger' => 'rejected',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'approved',
                        'heroicon-o-clock' => 'pending',
                        'heroicon-o-x-circle' => 'rejected',
                    ])
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->label('Registered On')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('last_renewed_at', 'desc')
            ->recordActions([
                // ViewAction::make(),
                // EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->filters([
                Filter::make('renewed_this_month')
                    ->label('Renewed This Month')
                    ->query(fn (Builder $query) => 
                        $query->whereMonth('last_renewed_at', now()->month)
                              ->whereYear('last_renewed_at', now()->year)
                    ),
                
                Filter::make('renewed_this_year')
                    ->label('Renewed This Year')
                    ->query(fn (Builder $query) => 
                        $query->whereYear('last_renewed_at', now()->year)
                    ),
                
                SelectFilter::make('renewal_count')
                    ->label('Renewal Count')
                    ->options([
                        '1' => '1 renewal',
                        '2' => '2 renewals',
                        '3' => '3+ renewals',
                        '5' => '5+ renewals',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (!isset($data['value'])) return;
                        
                        $count = (int) $data['value'];
                        if ($count === 3) {
                            $query->where('renewal_count', '>=', 3);
                        } elseif ($count === 5) {
                            $query->where('renewal_count', '>=', 5);
                        } else {
                            $query->where('renewal_count', $count);
                        }
                    }),
                
                Filter::make('expiring_soon')
                    ->label('Expiring Soon Again')
                    ->query(fn (Builder $query) => 
                        $query->whereBetween('card_valid_until', [now(), now()->addDays(30)])
                    ),
            ])
            ->paginated([10, 25, 50, 100]);
    }
}

