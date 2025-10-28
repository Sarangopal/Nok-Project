<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentRenewals extends BaseWidget
{
    protected static ?string $heading = 'Recent Renewal Requests';

    protected int|string|array $columnSpan = 'full';

    public function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->query(
                // Show only members who have submitted renewal requests
                // These are members who clicked "Request Renewal" from their dashboard
                Registration::query()
                    ->whereNotNull('renewal_requested_at')
                    ->orderBy('renewal_requested_at', 'desc')
            )
            ->defaultPaginationPageOption(5)
            ->paginated([5])
            ->columns([
                TextColumn::make('memberName')->label('Member')->searchable(),
                TextColumn::make('nok_id')->label('NOK ID')->searchable(),
                TextColumn::make('email')->label('Email')->searchable(),
                BadgeColumn::make('renewal_status')
                    ->label('Status')
                    ->colors([
                        'warning' => 'pending',
                        'success' => 'approved',
                    ]),
                TextColumn::make('renewal_requested_at')
                    ->label('Requested On')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->description(function ($record) {
                        if (!$record->renewal_requested_at) return null;
                        $days = (int) abs(now()->diffInDays($record->renewal_requested_at, false));
                        if ($days == 0) return 'Today';
                        if ($days == 1) return 'Yesterday';
                        if ($days < 30) return "{$days} days ago";
                        $months = (int) round($days/30);
                        return $months . ' month' . ($months > 1 ? 's' : '') . ' ago';
                    }),
                TextColumn::make('card_valid_until')
                    ->label('Card Expires')
                    ->date('d M Y')
                    ->color(function ($record) {
                        if (!$record->card_valid_until) return null;
                        $days = (int) now()->diffInDays($record->card_valid_until, false);
                        if ($days < 0) return 'danger';
                        if ($days <= 30) return 'warning';
                        return 'success';
                    }),
            ])
            ->defaultSort('renewal_requested_at', 'DESC');
    }
}


