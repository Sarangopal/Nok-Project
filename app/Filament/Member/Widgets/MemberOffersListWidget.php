<?php

namespace App\Filament\Member\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class MemberOffersListWidget extends Widget
{
    protected static ?int $sort = 3;
    
    protected int | string | array $columnSpan = 'full';

    public static function canView(): bool
    {
        return Auth::guard('members')->check();
    }

    protected function getViewData(): array
    {
        $member = Auth::guard('members')->user();
        
        $offers = collect();
        if ($member) {
            $offers = $member->offers()
                ->orderBy('created_at', 'desc')
                ->get();
        }
        
        return [
            'member' => $member,
            'offers' => $offers,
        ];
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('filament.member.widgets.member-offers-list', $this->getViewData());
    }
}





