<?php

namespace App\Filament\Widgets;

use App\Models\Registration;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Support\Carbon;

class ExpiringSoon extends BaseWidget
{
    protected static ?string $heading = 'Cards Expiring in 30 Days';

    protected int|string|array $columnSpan = 'full';
       // ✅ Correct syntax for column span (optional)
       protected static ?int $sort = 3;

    public function table(Tables\Table $table): Tables\Table
    {
        $now = now();
        $limit = now()->addDays(30);

        return $table
            ->query(
                Registration::query()
                    // Show members who are approved (either as new member OR renewal)
                    ->where(function($query) {
                        $query->where('login_status', 'approved')
                              ->orWhere('renewal_status', 'approved');
                    })
                    ->whereNotNull('card_valid_until')
                    ->whereBetween('card_valid_until', [$now, $limit])
                    ->orderBy('card_valid_until')
            )
            ->defaultPaginationPageOption(5)
            ->paginated([5])
            ->columns([
                TextColumn::make('memberName')
                    ->label('Member')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('nok_id')
                    ->label('NOK ID')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('mobile')
                    ->label('Mobile')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
                TextColumn::make('card_valid_until')
                    ->label('Expires On')
                    ->date('d M Y')
                    ->sortable()
                    ->description(function ($record) {
                        if (!$record->card_valid_until) return null;
                        // Calculate days as integer (no decimals)
                        $days = (int) now()->diffInDays($record->card_valid_until, false);
                        
                        if ($days < 0) return '🔴 Expired ' . abs($days) . ' days ago';
                        if ($days == 0) return '🔴 Expires Today!';
                        if ($days == 1) return '🟠 Expires Tomorrow!';
                        if ($days <= 7) return "🔴 {$days} days remaining";
                        if ($days <= 15) return "🟠 {$days} days remaining";
                        return "🟢 {$days} days remaining";
                    })
                    ->color(function ($record) {
                        if (!$record->card_valid_until) return null;
                        // Calculate days as integer
                        $days = (int) now()->diffInDays($record->card_valid_until, false);
                        
                        // Color logic:
                        // RED (danger) = expired or <= 7 days
                        // YELLOW (warning) = 8-15 days
                        // GREEN (success) = 16-30 days
                        if ($days < 0) return 'danger';     // Expired - RED
                        if ($days == 0) return 'danger';    // Today - RED
                        if ($days <= 7) return 'danger';    // 1-7 days - RED
                        if ($days <= 15) return 'warning';  // 8-15 days - YELLOW
                        return 'success';                    // 16-30 days - GREEN
                    }),
                
                TextColumn::make('renewal_status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'approved',
                        'warning' => 'pending',
                        'danger' => 'rejected',
                    ])
                    ->toggleable(isToggledHiddenByDefault: false),
            ])
            ->defaultSort('card_valid_until', 'asc');
    }
}



