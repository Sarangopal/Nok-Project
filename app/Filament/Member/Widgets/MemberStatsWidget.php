<?php

namespace App\Filament\Member\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class MemberStatsWidget extends BaseWidget
{
    protected static ?int $sort = 0;
    
    protected int | string | array $columnSpan = 'full';

    protected function getStats(): array
    {
        $member = Auth::guard('members')->user();

        if (!$member) {
            return [];
        }

        $memberSince = $member->doj ? (is_string($member->doj) ? $member->doj : $member->doj->format('M d, Y')) : 'N/A';
        $offersCount = $member->offers()->where('active', true)->count();
        $validUntil = $member->card_valid_until ? (is_string($member->card_valid_until) ? $member->card_valid_until : $member->card_valid_until->format('M d, Y')) : 'N/A';
        
        $statusColor = match($member->renewal_status) {
            'approved' => 'success',
            'pending' => 'warning',
            'rejected' => 'danger',
            default => 'gray'
        };

        return [
            Stat::make('Membership Status', ucfirst($member->renewal_status ?? 'Unknown'))
                ->description('Current status')
                ->color($statusColor)
                ->icon('heroicon-o-shield-check'),
            
            Stat::make('Member Since', $memberSince)
                ->description('Joining date')
                ->color('info')
                ->icon('heroicon-o-calendar'),
            
            Stat::make('Exclusive Offers', $offersCount)
                ->description('Available to you')
                ->color('warning')
                ->icon('heroicon-o-gift'),
            
            Stat::make('Valid Until', $validUntil)
                ->description('Card expiry')
                ->color($member->card_valid_until && !is_string($member->card_valid_until) && $member->card_valid_until->isPast() ? 'danger' : 'success')
                ->icon('heroicon-o-identification'),
        ];
    }
}

