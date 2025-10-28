<?php

namespace App\Filament\Member\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class MemberOffersWidget extends Widget
{
    protected static ?string $pollingInterval = null;

    protected int | string | array $columnSpan = 'full';

    public function getOffers()
    {
        $member = Auth::guard('members')->user();
        
        if (!$member) {
            return collect();
        }

        return $member->offers()
            ->orderBy('created_at', 'desc')
            ->get();
    }

    protected function getViewData(): array
    {
        return [
            'offers' => $this->getOffers(),
        ];
    }

    public static function getView(): string
    {
        return 'filament.member.widgets.member-offers';
    }
}
