<?php

declare(strict_types=1);

return [
    'panel_route' => env('PANEL_ROUTE', 'admin'),
    'only_filament' => env('ONLY_FILAMENT', true),
    'admin_email' => env('ADMIN_EMAIL', 'admin@example.com'),
    'admin_password' => env('ADMIN_PASSWORD', 'password'),
    'admin_user' => env('ADMIN_USER', 'admin'),
    'admin_firstname' => env('ADMIN_FIRSTNAME', 'Super'),
    'admin_lastname' => env('ADMIN_LASTNAME', 'Admin'),
    'top_nav_enabled' => env('TOP_NAV_ENABLED', true),
    'topbar_enabled' => env('TOPBAR_ENABLED', true),
    'github_url' => env('GITHUB_URL', 'https://github.com/akrista/fillakit'),
    'docs_url' => env('DOCS_URL', 'https://fillakit.notakrista.com/docs'),
];
