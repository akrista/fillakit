<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use Composer\InstalledVersions;
use Filament\Widgets\Widget;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\DB;
use PDO;

final class FillakitInfo extends Widget
{
    protected static ?int $sort = -2;

    protected static bool $isLazy = false;

    /**
     * @var view-string
     */
    protected string $view = 'filament.widgets.fillakit-info-widget';

    /**
     * @return array<string, mixed>
     */
    public function getViewData(): array
    {
        return [
            'app_name' => config('app.name', 'Fillakit'),
            'app_version' => config('fillakit.version', '1.0.0'),
            'filament_version' => InstalledVersions::getPrettyVersion('filament/filament'),
            'laravel_version' => Application::VERSION,
            'php_version' => PHP_VERSION,
            'db_version' => ucfirst(DB::connection()->getDriverName()) . ' ' . DB::connection()->getPdo()->getAttribute(PDO::ATTR_SERVER_VERSION),
            'github_url' => config('fillakit.github_url', 'https://github.com/akrista/fillakit'),
        ];
    }
}
