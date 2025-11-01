<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class MemberPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('member')
            ->path('member/panel')
            ->login(\App\Filament\Member\Pages\Auth\Login::class)
            ->brandName('🎫 Member Portal')
            ->brandLogo(asset('img/NOKlogo.png'))
            ->brandLogoHeight('3rem')
            ->favicon(asset('img/NOKlogo.png'))
            ->colors([
                'primary' => [
                    50 => '240, 249, 255',
                    100 => '224, 242, 254',
                    200 => '186, 230, 253',
                    300 => '125, 211, 252',
                    400 => '56, 189, 248',
                    500 => '14, 165, 233',
                    600 => '2, 132, 199',
                    700 => '3, 105, 161',
                    800 => '7, 89, 133',
                    900 => '12, 74, 110',
                    950 => '8, 47, 73',
                ],
                'danger' => Color::Red,
                'gray' => Color::Zinc,
                'info' => Color::Blue,
                'success' => Color::Green,
                'warning' => Color::Amber,
            ])
            ->font('Inter')
            ->authGuard('members')
            ->authPasswordBroker('members')
            ->discoverResources(app_path('Filament/Member/Resources'), 'App\\Filament\\Member\\Resources')
            ->discoverPages(app_path('Filament/Member/Pages'), 'App\\Filament\\Member\\Pages')
            ->pages([
                \App\Filament\Member\Pages\MemberDashboard::class,
                \App\Filament\Member\Pages\EditProfile::class,
            ])
            ->discoverWidgets(app_path('Filament/Member/Widgets'), 'App\\Filament\\Member\\Widgets')
            ->widgets([
                AccountWidget::class,
            ])
            ->darkMode(true)
            ->topNavigation(false)
            ->sidebarCollapsibleOnDesktop()
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}

