<?php

namespace App\Filament\Member\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class MemberCardWidget extends Widget
{
    protected static ?int $sort = 2;
    
    protected int | string | array $columnSpan = [
        'default' => 'full',
        'md' => 1,
        'lg' => 1,
    ];

    public static function canView(): bool
    {
        return Auth::guard('members')->check();
    }

    protected function getViewData(): array
    {
        $member = Auth::guard('members')->user();
        
        return [
            'member' => $member,
            'downloadUrl' => route('membership.card.download', $member->id ?? 0),
        ];
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('filament.member.widgets.member-card', $this->getViewData());
    }
}

