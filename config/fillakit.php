<?php

declare(strict_types=1);

return [
    'panel_route' => env('PANEL_ROUTE', 'admin'),
    'only_filament' => env('ONLY_FILAMENT', true),
    'admin_email' => env('ADMIN_EMAIL', 'admin@example.com'),
    'admin_password' => env('ADMIN_PASSWORD', 'password'),
    'admin_user' => env('ADMIN_USER', 'admin'),
    'top_nav_enabled' => env('TOP_NAV_ENABLED', true),
    'github_url' => 'https://github.com/akrista/fillakit',
];
