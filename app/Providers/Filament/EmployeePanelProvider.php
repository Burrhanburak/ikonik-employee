<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use App\Models\Employee;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Employee\Pages\Auth\Register;
use App\Filament\Widgets\EmployeeStatsWidget;
use App\Filament\Widgets\RecentCheckinsWidget;
use App\Filament\Widgets\TodayShiftWidget;
use App\Filament\Widgets\NotificationsWidget;

class EmployeePanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('employee')
            ->path('employee')
            ->login()
            ->registration(Register::class)
            ->domain('employee.employee.test')
            ->authGuard('employee')
            ->brandName('İkon insan kaynakalrı çalışan paneli')
            ->brandLogo(asset('images/ikon-ik-logo.svg'))
            ->brandLogoHeight('3rem')
            ->passwordReset()
            ->emailVerification()
            ->emailChangeVerification()
            ->authPasswordBroker('employees')
            ->colors([
                'primary' => Color::Orange,
                
            ])
            // ->topNavigation()
            // ->spa()
            ->profile()
            ->viteTheme('resources/css/filament/employee/theme.css')
            ->discoverResources(in: app_path('Filament/Employee/Resources'), for: 'App\Filament\Employee\Resources')
            ->discoverPages(in: app_path('Filament/Employee/Pages'), for: 'App\Filament\Employee\Pages')
            ->pages([
                Dashboard::class,
                \App\Filament\Employee\Pages\PublicCheckInPage::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Employee/Widgets'), for: 'App\Filament\Employee\Widgets')
            ->widgets([
                AccountWidget::class,
                // \App\Filament\Widgets\EmployeeStatsWidget::class,
                // \App\Filament\Widgets\RecentCheckinsWidget::class,
                // \App\Filament\Widgets\TodayShiftWidget::class,
                // \App\Filament\Widgets\NotificationsWidget::class,
           
            ])
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
