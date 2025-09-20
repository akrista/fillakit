<?php

declare(strict_types=1);

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\Converge\DocsModuleProvider::class,
    App\Providers\Filament\AdminPanelProvider::class,
    App\Providers\HorizonServiceProvider::class,
    App\Providers\MemoryMonitorServiceProvider::class,
    App\Providers\PolicyServiceProvider::class,
];
