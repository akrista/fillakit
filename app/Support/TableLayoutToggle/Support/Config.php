<?php

declare(strict_types=1);

namespace App\Support\TableLayoutToggle\Support;

use App\Support\TableLayoutToggle\Persisters\LocalStoragePersister;
use BackedEnum;

final class Config
{
    public static function defaultLayout(): string
    {
        return config('table-layout-toggle.default_layout', 'list');
    }

    /**
     * @return class-string<\App\Support\TableLayoutToggle\Contracts\LayoutPersister>
     */
    public static function shouldPersistLayoutUsing(): string
    {
        return config('table-layout-toggle.persist.persister', LocalStoragePersister::class);
    }

    public static function shouldShareLayoutBetweenPages(): bool
    {
        return config('table-layout-toggle.persist.share_between_pages', false);
    }

    public static function toggleActionEnabled(): bool
    {
        return config('table-layout-toggle.toggle_action.enabled', true);
    }

    public static function toggleActionPosition(): string
    {
        return config('table-layout-toggle.toggle_action.position', 'tables::toolbar.search.after');
    }

    public static function getListLayoutButtonIcon(): string | BackedEnum
    {
        return config('table-layout-toggle.toggle_action.list_icon', 'heroicon-o-list-bullet');
    }

    public static function getGridLayoutButtonIcon(): string | BackedEnum
    {
        return config('table-layout-toggle.toggle_action.grid_icon', 'heroicon-o-squares-2x2');
    }
}
