<?php

namespace App\Filament\Resources\ReminderEmails\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Tables\Table;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class ReminderEmailsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('created_at')
                    ->label('Sent At')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('registration.nok_id')
                    ->label('NOK ID')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('member_name')
                    ->label('Member Name')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->email),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('registration.mobile')
                    ->label('Mobile')
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: false),

                TextColumn::make('registration.whatsapp')
                    ->label('WhatsApp')
                    ->searchable()
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('registration.department')
                    ->label('Department')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('registration.institution')
                    ->label('Institution')
                    ->searchable()
                    ->limit(30)
                    ->tooltip(fn ($record) => $record->registration?->institution)
                    ->toggleable(isToggledHiddenByDefault: true),

                BadgeColumn::make('days_before_expiry')
                    ->label('Reminder Type')
                    ->colors([
                        'danger' => 0,
                        'warning' => fn ($state) => in_array($state, [1, 7]),
                        'info' => 15,
                        'success' => 30,
                    ])
                    ->formatStateUsing(function ($state) {
                        if ($state === 0) return 'Expired Today';
                        if ($state === 1) return '1 Day Before';
                        return "{$state} Days Before";
                    })
                    ->sortable(),

                TextColumn::make('card_valid_until')
                    ->label('Card Expiry')
                    ->date('d M Y')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'success' => 'sent',
                        'danger' => 'failed',
                    ])
                    ->icons([
                        'heroicon-o-check-circle' => 'sent',
                        'heroicon-o-x-circle' => 'failed',
                    ])
                    ->sortable(),

                TextColumn::make('error_message')
                    ->label('Error')
                    ->limit(50)
                    ->tooltip(fn ($record) => $record->error_message)
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->color('danger'),
            ])
            ->defaultSort('created_at', 'desc')
       
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->filters([
                SelectFilter::make('days_before_expiry')
                    ->label('Reminder Type')
                    ->options([
                        '30' => '30 Days Before',
                        '15' => '15 Days Before',
                        '7' => '7 Days Before',
                        '1' => '1 Day Before',
                        '0' => 'Expired Today',
                    ]),

                SelectFilter::make('status')
                    ->options([
                        'sent' => 'Sent Successfully',
                        'failed' => 'Failed',
                    ]),

                Filter::make('today')
                    ->label('Sent Today')
                    ->query(fn (Builder $query) => $query->whereDate('created_at', today())),

                Filter::make('this_week')
                    ->label('This Week')
                    ->query(fn (Builder $query) => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])),

                Filter::make('this_month')
                    ->label('This Month')
                    ->query(fn (Builder $query) => $query->whereMonth('created_at', now()->month)
                        ->whereYear('created_at', now()->year)),
            ])
            ->paginated([10, 25, 50, 100]);
    }
}



