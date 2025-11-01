<?php

namespace App\Filament\Member\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class MemberProfileWidget extends Widget
{
    protected static ?int $sort = 1;
    
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
        return [
            'member' => Auth::guard('members')->user(),
        ];
    }

    public function render(): \Illuminate\Contracts\View\View
    {
        return view('filament.member.widgets.member-profile', $this->getViewData());
    }
}

