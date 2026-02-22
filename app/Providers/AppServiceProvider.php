<?php

declare(strict_types=1);

namespace App\Providers;

use App\Settings\GeneralSettings;
use Carbon\CarbonImmutable;
use Exception;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Sleep;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Laravel\Octane\Facades\Octane;

final class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->configureDefaults();
        Table::configureUsing(function (Table $table): void {
            $table
                ->emptyStateHeading('No data yet')
                ->emptyStateDescription('Once there is data it will show up here')
                ->striped()
                ->poll('10s')
                ->defaultPaginationPageOption(6)
                ->paginated([6, 24, 64, 86, 'all'])
                ->extremePaginationLinks()
                ->deferLoading()
                ->persistFiltersInSession()
                ->defaultSort('created_at', 'desc');
        });
        Page::$reportValidationErrorUsing = function (ValidationException $exception): void {
            Notification::make()
                ->title($exception->getMessage())
                ->danger()
                ->send();
        };

        $this->app->booted(function (): void {
            try {
                $settings = resolve(GeneralSettings::class);

                config([
                    'laravelpwa.manifest.theme_color' => $settings->pwa_theme_color,
                    'laravelpwa.manifest.background_color' => $settings->pwa_background_color,
                ]);
            } catch (Exception) {
                // Settings not available yet (e.g., during migrations)
            }
        });
    }

    /**
     * Configure default behaviors for production-ready applications.
     */
    private function configureDefaults(): void
    {
        Sleep::fake();
        Model::shouldBeStrict();
        Model::automaticallyEagerLoadRelationships();
        Date::use(CarbonImmutable::class);
        Password::defaults(
            Password::min(12)
                ->max(21)
                ->mixedCase()
                ->letters()
                ->numbers()
                ->symbols()
                ->uncompromised(3)
        );
        if (config('app.env') === 'production') {
            URL::forceHttps();
            DB::prohibitDestructiveCommands();
            DB::reconnect();
            Octane::tick('reconnect-database', DB::reconnect(...), 300);
        }

        Http::preventStrayRequests();
        Vite::useAggressivePrefetching();
    }
}
