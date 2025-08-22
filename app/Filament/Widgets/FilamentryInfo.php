<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class FilamentryInfo extends Widget
{
    protected static ?int $sort = -2;

    protected static bool $isLazy = false;

    /**
     * @var view-string
     */
    protected string $view = 'filament.widgets.filamentry-info-widget';

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        return [
            'app_name' => config('app.name', 'Filamentry'),
            'app_version' => config('filamentry.version', '1.0.0'),
            'filament_version' => \Composer\InstalledVersions::getPrettyVersion('filament/filament'),
            'laravel_version' => \Illuminate\Foundation\Application::VERSION,
            'php_version' => PHP_VERSION,
            'db_version' => ucfirst(\Illuminate\Support\Facades\DB::connection()->getDriverName()) . ' ' . \Illuminate\Support\Facades\DB::connection()->getPdo()->getAttribute(\PDO::ATTR_SERVER_VERSION),
            'github_url' => config('filamentry.github_url', 'https://github.com/akrista/filamentry'),
        ];
    }
}
