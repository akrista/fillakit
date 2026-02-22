<?php

declare(strict_types=1);

namespace App\Support\TableLayoutToggle\Concerns;

use App\Support\TableLayoutToggle\Contracts\LayoutPersister;
use App\Support\TableLayoutToggle\Support\Config;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\View\View;

trait HasToggleableTable
{
    public ?string $layoutView = null;

    protected LayoutPersister $layoutPersister;

    public function initializeHasToggleableTable(): void
    {
        $this->listeners += [
            'changeLayoutView' => 'changeLayoutView',
        ];
    }

    public function bootHasToggleableTable(): void
    {
        $persisterClass = Config::shouldPersistLayoutUsing();

        $this->layoutPersister = new $persisterClass($this);

        $this->configurePersister();

        $this->layoutPersister->onComponentBoot();

        $this->layoutView = $this->layoutPersister->getState() ?: $this->layoutView;
    }

    public function configurePersister(): void
    {
        //
    }

    public function bootedHasToggleableTable(): void
    {
        $this->layoutPersister->onComponentBooted();

        if (Config::toggleActionEnabled() && ($filamentHook = Config::toggleActionPosition())) {
            $this->registerLayoutViewToggleActionHook($filamentHook);
        }
    }

    public function getDefaultLayoutView(): string
    {
        return Config::defaultLayout();
    }

    public function isGridLayout(): bool
    {
        return $this->getLayoutView() === 'grid';
    }

    public function isListLayout(): bool
    {
        return $this->getLayoutView() === 'list';
    }

    public function getLayoutView(): string
    {
        return $this->layoutView ?? $this->getDefaultLayoutView();
    }

    public function changeLayoutView(): void
    {
        $this->layoutView = $this->isListLayout() ? 'grid' : 'list';

        $this->layoutPersister->setState($this->layoutView);

        $this->resetTable();
    }

    protected function registerLayoutViewToggleActionHook(string $filamentHook): void
    {
        FilamentView::registerRenderHook(
            $filamentHook,
            fn (): View => view('filament.table-layout-toggle.toggle-action', [
                'gridIcon' => Config::getGridLayoutButtonIcon(),
                'listIcon' => Config::getListLayoutButtonIcon(),
            ]),
            scopes: static::class,
        );
    }
}
