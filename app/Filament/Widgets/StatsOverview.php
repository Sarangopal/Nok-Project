<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Registration;
use App\Models\ContactMessage;

class StatsOverview extends StatsOverviewWidget
{
     // ✅ Correct syntax for column span (optional)
     protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalMembers = Registration::count();
        // Count members approved either as new members OR through renewal
        $approvedMembers = Registration::where(function($query) {
            $query->where('login_status', 'approved')
                  ->orWhere('renewal_status', 'approved');
        })->count();
        $pendingMembers = Registration::where('login_status', 'pending')
            ->where('renewal_status', 'pending')
            ->count();
        // Total renewals = members who have renewed at least once
        $totalRenewals = Registration::where('renewal_count', '>', 0)->count();
        // Pending renewals = members who submitted renewal requests (renewal_requested_at set) and status is pending
        $pendingRenewals = Registration::whereNotNull('renewal_requested_at')
            ->where('renewal_status', 'pending')
            ->count();
        $enquiries = ContactMessage::count();

        return [
            Stat::make('Total Members', $totalMembers)
                ->description('All registered members')
                ->descriptionIcon('heroicon-m-users')
                ->chart([7, 12, 15, 20, 25, 30, $totalMembers])
                ->color('info')
                ->extraAttributes([
                    'class' => 'stat-gradient-blue',
                ]),

            Stat::make('Active Members', $approvedMembers)
                ->description('Approved & Active')
                ->descriptionIcon('heroicon-m-check-badge')
                ->chart([5, 10, 12, 15, 18, 22, $approvedMembers])
                ->color('success')
                ->extraAttributes([
                    'class' => 'stat-gradient-green',
                ]),

            Stat::make('Pending Approvals', $pendingMembers)
                ->description('Awaiting review')
                ->descriptionIcon('heroicon-m-clock')
                ->chart([2, 3, 5, 4, 6, 8, $pendingMembers])
                ->color('warning')
                ->extraAttributes([
                    'class' => 'stat-gradient-orange',
                ]),

            Stat::make('Total Renewals', $totalRenewals)
                ->description('All time renewals')
                ->descriptionIcon('heroicon-m-arrow-path')
                ->chart([3, 6, 9, 12, 15, 18, $totalRenewals])
                ->color('primary')
                ->extraAttributes([
                    'class' => 'stat-gradient-purple',
                ]),

            Stat::make('Pending Renewals', $pendingRenewals)
                ->description('Needs attention')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->chart([1, 2, 3, 4, 5, 6, $pendingRenewals])
                ->color('danger')
                ->extraAttributes([
                    'class' => 'stat-gradient-red',
                ]),

            Stat::make('Enquiries', $enquiries)
                ->description('Contact messages')
                ->descriptionIcon('heroicon-m-chat-bubble-bottom-center-text')
                ->chart([2, 4, 6, 8, 10, 12, $enquiries])
                ->color('info')
                ->extraAttributes([
                    'class' => 'stat-gradient-cyan',
                ]),
        ];
    }
}
