<?php

namespace App\Filament\Member\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class MembershipCard extends Widget
{
    protected static ?string $pollingInterval = null;

    protected int | string | array $columnSpan = 'full';

    public function getMember()
    {
        return Auth::guard('members')->user();
    }

    public function getCardStatus(): string
    {
        $member = $this->getMember();
        
        if (!$member) {
            return 'unknown';
        }

        if ($member->renewal_status !== 'approved') {
            return 'pending';
        }

        if (!$member->card_valid_until) {
            return 'no_expiry';
        }

        $now = now();
        $validUntil = $member->card_valid_until;

        if ($validUntil->isPast()) {
            return 'expired';
        }

        if ($validUntil->diffInDays($now) <= 30) {
            return 'expiring_soon';
        }

        return 'active';
    }

    protected function getViewData(): array
    {
        return [
            'member' => $this->getMember(),
            'status' => $this->getCardStatus(),
        ];
    }

    public static function getView(): string
    {
        return 'filament.member.widgets.membership-card';
    }
}
