<?php

declare(strict_types=1);

namespace App\Filament\Pages;

use App\Filament\Widgets\FillakitInfo;
use BackedEnum;
use Filament\Pages\Dashboard as PagesDashboard;
use Filament\Support\Icons\Heroicon;
use Filament\Widgets\AccountWidget;
use Override;

final class Dashboard extends PagesDashboard
{
    #[Override]
    protected static ?string $navigationLabel = 'Dashboard';

    #[Override]
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHome;

    #[Override]
    protected static ?int $navigationSort = -2;

    public function getWidgets(): array
    {
        return [
            FillakitInfo::class,
            AccountWidget::class,
        ];
    }
}
