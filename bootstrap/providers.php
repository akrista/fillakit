<?php

declare(strict_types=1);

use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\HorizonServiceProvider;
use App\Providers\MemoryMonitorServiceProvider;
use App\Providers\PolicyServiceProvider;

return [
    AppServiceProvider::class,
    AdminPanelProvider::class,
    HorizonServiceProvider::class,
    PolicyServiceProvider::class,
    MemoryMonitorServiceProvider::class,
];
