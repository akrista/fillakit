<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('general.brand_name', config('app.name'));
        $this->migrator->add('general.brand_logo', 'sites/logo.png');
        $this->migrator->add('general.brand_logo_height', '2');
        $this->migrator->add('general.brand_logo_height_unit', 'rem');
        $this->migrator->add('general.site_favicon', 'sites/logo.ico');
        $this->migrator->add('general.search_engine_indexing', false);
        $this->migrator->add('general.site_theme', [
            'primary' => '#F22B02',
            'secondary' => '#4B5563',
            'gray' => null,
            'success' => '#10B981',
            'danger' => '#EF4444',
            'info' => '#3B82F6',
            'warning' => '#F59E0B',
        ]);
    }
};
