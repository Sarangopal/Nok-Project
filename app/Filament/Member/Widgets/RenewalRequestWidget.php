<?php

namespace App\Filament\Member\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class RenewalRequestWidget extends Widget
{
    protected static ?int $sort = 1;
    
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return Auth::guard('members')->check();
    }

    protected function getViewData(): array
    {
        $member = Auth::guard('members')->user();

        if (!$member) {
            return [
                'member' => null,
                'showButton' => false,
                'buttonText' => '',
                'buttonColor' => 'gray',
                'daysLeft' => null,
                'isPending' => false,
                'isExpired' => false,
            ];
        }

        // Calculate days left
        $daysLeft = null;
        $isExpired = false;
        $isExpiringSoon = false;

        if ($member->card_valid_until) {
            $daysLeft = now()->diffInDays($member->card_valid_until, false);
            $isExpired = $daysLeft < 0;
            $isExpiringSoon = $daysLeft >= 0 && $daysLeft <= 30;
        }

        // Check if renewal is pending
        $isPending = $member->renewal_status === 'pending' && $member->renewal_requested_at;

        // Determine if button should be shown
        $showButton = ($isExpired || $isExpiringSoon) && !$isPending;

        // Button text based on status
        $buttonText = $isExpired ? 'Request Renewal Now' : 'Request Early Renewal';
        
        // Button color
        $buttonColor = $isExpired ? 'danger' : 'warning';

        return [
            'member' => $member,
            'showButton' => $showButton,
            'buttonText' => $buttonText,
            'buttonColor' => $buttonColor,
            'daysLeft' => $daysLeft,
            'isPending' => $isPending,
            'isExpired' => $isExpired,
            'isExpiringSoon' => $isExpiringSoon,
        ];
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('filament.member.widgets.renewal-request', $this->getViewData());
    }
}

