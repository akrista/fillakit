<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Widgets\FillakitInfo;
use App\Filament\Widgets\FillakitPackages;
use App\Filament\Widgets\StatsOverview;
use BackedEnum;
use Filament\Pages\Dashboard as PagesDashboard;
use Filament\Support\Icons\Heroicon;

final class Dashboard extends PagesDashboard
{
    protected static ?string $navigationLabel = 'Home';

    protected static ?string $title = '';

    protected ?string $heading = 'Fill-a-Kit';

    protected ?string $subheading = '🚀 Your all-in-one Filament starter kit ✨';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?int $navigationSort = -2;

    public function getWidgets(): array
    {
        return [
            FillakitInfo::class,
            StatsOverview::class,
            FillakitPackages::class,
        ];
    }
}
