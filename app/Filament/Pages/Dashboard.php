<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Widgets\FilamentryInfo;
use BackedEnum;
use Filament\Pages\Dashboard as PagesDashboard;
use Filament\Support\Icons\Heroicon;

final class Dashboard extends PagesDashboard
{
    protected static ?string $navigationLabel = 'Dashboard';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    protected static ?int $navigationSort = -2;

    public function getWidgets(): array
    {
        return [
            FilamentryInfo::class,
        ];
    }
}
