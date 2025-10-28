<?php

namespace App\Filament\Member\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Filament\Widgets\Widget;

class MemberDashboard extends BaseDashboard
{
    protected static string $routePath = '/';
    
    protected static ?string $title = 'My Dashboard';
    
    public function getWidgets(): array
    {
        return [
            \App\Filament\Member\Widgets\MemberStatsWidget::class,
            \App\Filament\Member\Widgets\MemberProfileTableWidget::class,
            \App\Filament\Member\Widgets\RenewalRequestWidget::class,
            \App\Filament\Member\Widgets\MemberCardWidget::class,
            \App\Filament\Member\Widgets\MemberOffersListWidget::class,
        ];
    }
    
    public function getColumns(): int | array
    {
        return [
            'default' => 1,
            'sm' => 1,
            'md' => 2,
            'lg' => 2,
            'xl' => 2,
            '2xl' => 2,
        ];
    }
}

