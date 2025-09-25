<?php

declare(strict_types=1);

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

final class StatsOverview extends StatsOverviewWidget
{
    protected static ?int $sort = -1;

    protected function getStats(): array
    {
        return [
            Stat::make('Installs', '1,248')
                ->description('Last 30 days')
                ->color('success')
                ->chart([7, 12, 9, 14, 18, 21, 24]),

            Stat::make('Visits', '8,913')
                ->description('Unique visitors')
                ->color('primary')
                ->chart([4, 6, 8, 6, 9, 11, 15]),

            Stat::make('GitHub Stars', '230+')
                ->description('Growing community')
                ->color('warning')
                ->chart([2, 3, 5, 8, 13, 21, 34]),
        ];
    }
}
