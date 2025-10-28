<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use Composer\InstalledVersions;
use Filament\Widgets\Widget;

final class FillakitPackages extends Widget
{
    protected static ?int $sort = 1;

    /**
     * @var view-string
     */
    protected string $view = 'filament.widgets.fillakit-packages-widget';

    protected int|string|array $columnSpan = 'full';

    /**
     * @return array<string, mixed>
     */
    public function getViewData(): array
    {
        $packages = [
            'laravel/framework' => 'Laravel',
            'filament/filament' => 'Filament',
            'inertiajs/inertia-laravel' => 'Inertia',
            'tightenco/ziggy' => 'Ziggy',
            'laravel/horizon' => 'Horizon',
            'laravel/octane' => 'Octane',
            'laravel/reverb' => 'Reverb',
            'spatie/laravel-permission' => 'Spatie Permission',
            'spatie/laravel-settings' => 'Spatie Settings',
        ];

        $stack = [];

        foreach ($packages as $name => $label) {
            $version = InstalledVersions::isInstalled($name)
                ? InstalledVersions::getPrettyVersion($name)
                : null;

            if ($version !== null) {
                $stack[] = [
                    'label' => $label,
                    'package' => $name,
                    'version' => $version,
                ];
            }
        }

        return [
            'stack' => $stack,
        ];
    }
}
