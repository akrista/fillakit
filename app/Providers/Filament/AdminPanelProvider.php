<?php

declare(strict_types=1);

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use App\Filament\Pages\Auth\Register;
use App\Filament\Pages\Dashboard;
use App\Settings\GeneralSettings;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Auth\MultiFactor\Email\EmailAuthentication;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Platform;
use Filament\View\PanelsRenderHook;
use Illuminate\Contracts\View\View;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Middleware\ShareErrorsFromSession;

final class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id(config('fillakit.panel_route'))
            ->path(config('fillakit.only_filament') ? '/' : '/' . config('fillakit.panel_route'))
            ->profile(
                // page: EditProfile::class,
                isSimple: false
            )
            ->multiFactorAuthentication([
                AppAuthentication::make()
                    ->brandName(app(GeneralSettings::class)->brand_name ?? config('app.name'))
                    ->codeWindow(6)
                    ->recoverable()
                    ->regenerableRecoveryCodes(),
                EmailAuthentication::make()
                    ->codeExpiryMinutes(4),
            ], isRequired: false)
            ->login(action: Login::class)
            ->loginRouteSlug('login')
            ->registration(action: Register::class)
            ->registrationRouteSlug('register')
            ->passwordReset()
            // ->passwordResetRoutePrefix('password-reset')
            // ->passwordResetRequestRouteSlug('request')
            // ->passwordResetRouteSlug('reset')
            ->emailVerification()
            ->emailVerificationRouteSlug('verify')
            ->emailVerificationRoutePrefix('email-verification')
            ->emailVerificationPromptRouteSlug('prompt')
            // ->emailChangeVerification()
            // ->emailChangeVerificationRoutePrefix('email-change-verification')
            // ->emailChangeVerificationRouteSlug('verify')
            ->revealablePasswords(true)
            // brisk theme
            // ->font('Kumbh Sans')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->colors(fn(GeneralSettings $settings): array => array_filter(array_map(
                Color::generateV3Palette(...),
                array_filter($settings->site_theme)
            )))
            ->brandName(fn(GeneralSettings $settings): string => $settings->brand_name ?? config('app.name'))
            ->brandLogo(fn(GeneralSettings $settings) => $settings->brand_logo && Storage::disk('public')->exists($settings->brand_logo) ? Storage::url($settings->brand_logo) : false)
            ->favicon(fn(GeneralSettings $settings) => in_array($settings->site_favicon, [null, '', '0'], true) ? null : Storage::url($settings->site_favicon))
            ->brandLogoHeight(
                fn(GeneralSettings $settings): string => ($settings->brand_logo_height && $settings->brand_logo_height_unit)
                    ? $settings->brand_logo_height . $settings->brand_logo_height_unit
                    : '2rem'
            )
            ->globalSearchKeyBindings(['command+shift+f', 'ctrl+shift+f'])
            ->globalSearchFieldSuffix(
                fn(): string => match (Platform::detect()) {
                    Platform::Windows,
                    Platform::Linux => 'Ctrl+Shift+F',
                    Platform::Mac => '⌘+⇧+F',
                    default => 'Ctrl+Shift+F',
                }
            )
            ->topbar(true)
            ->topNavigation(config('fillakit.top_nav_enabled'))
            ->sidebarCollapsibleOnDesktop(!config('fillakit.top_nav_enabled'))
            ->spa(condition: true, hasPrefetching: true)
            ->renderHook(
                PanelsRenderHook::HEAD_START,
                fn (): View => view('components.seo.meta'),
            )
            ->renderHook(
                name: PanelsRenderHook::BODY_END,
                hook: fn (): View => view('filament.switcher.switcher'),
            )
            ->unsavedChangesAlerts()
            ->databaseNotifications()
            ->databaseNotificationsPolling('60s')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->navigationGroups([])
            ->resources([])
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverClusters(in: app_path('Filament/Clusters'), for: 'App\\Filament\\Clusters')
            // ->clusters([])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([])
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
            ->plugins([]);
    }
}
