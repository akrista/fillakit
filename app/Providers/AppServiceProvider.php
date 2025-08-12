<?php

namespace App\Providers;

use Carbon\CarbonImmutable;
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

class AppServiceProvider extends ServiceProvider
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
        $this->configureEssentials();
        Table::configureUsing(function (Table $table): void {
            $table
                ->emptyStateHeading('No data yet')
                ->emptyStateDescription('Once there is data it will show up here')
                ->striped()
                ->poll('10s')
                ->defaultPaginationPageOption(10)
                ->paginated([10, 25, 50, 100])
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
    }

    private function configureEssentials(): void
    {
        Sleep::fake();
        Model::shouldBeStrict();
        Model::automaticallyEagerLoadRelationships();
        Date::use(CarbonImmutable::class);
        Password::defaults(fn(): ?Password => app()->isProduction() ? Password::min(12)->max(21)->uncompromised(3)->mixedCase()->letters()->numbers()->symbols() : null);
        if (config('app.env') === 'production') {
            URL::forceHttps();
            DB::prohibitDestructiveCommands();
        }

        Http::preventStrayRequests();
        Vite::useAggressivePrefetching();
    }
}
