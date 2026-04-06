<?php

namespace App\Providers\Filament;

use App\Filament\Auth\Login;
use Filament\Http\Middleware\Authenticate;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->brandName('Paulette Culture Kids')
            ->brandLogo(asset('images/logo.svg'))
            ->colors([
                'primary' => Color::hex('#0f9361'), // Emerald base
                'success' => Color::hex('#2d7c2d'),
                'warning' => Color::hex('#cc7c1a'),
                'danger' => Color::hex('#c5192d'),
                'info' => Color::hex('#0066cc'),
            ])
            ->viteTheme('resources/css/filament/admin-theme.css')
            ->font('Inter')
            ->sidebarCollapsibleOnDesktop()
            ->collapsibleNavigationGroups(true)
            ->navigationGroups([
                'PLATFORM' => NavigationGroup::make('PLATFORM')->collapsible(),
                'CONTENT' => NavigationGroup::make('CONTENT')->collapsible(),
                'SYSTEM' => NavigationGroup::make('SYSTEM')->collapsible(),
                'Settings' => NavigationGroup::make('Settings')
                    ->collapsible()
                    ->extraSidebarAttributes([
                        'class' => 'fi-sidebar-group--ck-settings',
                    ], merge: true),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
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
            ])
            ->renderHook(
                'panels::head.end',
                fn () => '<script src="https://cdn.jsdelivr.net/npm/chart.js@4/dist/chart.umd.min.js" defer></script>',
            );
    }
}
