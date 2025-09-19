<?php

namespace App\Providers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class MemoryMonitorServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        register_shutdown_function(function (): void {
            if (memory_get_usage() > 100 * 1024 * 1024) {
                Log::warning('High memory usage: ' . memory_get_usage());
            }
        });
    }
}
