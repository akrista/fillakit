<?php

declare(strict_types=1);

namespace App\Support\TableLayoutToggle\Persisters;

use App\Support\TableLayoutToggle\Contracts\LayoutPersister;
use App\Support\TableLayoutToggle\Support\Config;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Pages\ManageRecords;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Support\Facades\FilamentView;
use Filament\Widgets\TableWidget;
use Illuminate\Contracts\View\View;
use Livewire\Component;

abstract class AbstractPersister implements LayoutPersister
{
    protected string $cacheKey;

    public function __construct(
        protected Component $component,
    ) {
        $this->setKey($this->defaultKey());
    }

    final public function defaultKey(): string
    {
        $stateSharedBetweenComponents = Config::shouldShareLayoutBetweenPages();

        return $stateSharedBetweenComponents
            ? 'tableLayoutView'
            : 'tableLayoutView::' . md5($this->component::class);
    }

    final public function setKey(string $key): self
    {
        $this->cacheKey = $key;

        return $this;
    }

    final public function getKey(): string
    {
        return $this->cacheKey;
    }

    final public function onComponentBoot(): void
    {
        //
    }

    final public function onComponentBooted(): void
    {
        FilamentView::registerRenderHook(
            $this->getTableHookNameFromFilamentClassType(),
            fn (): View => $this->renderLayoutViewPersister(),
            scopes: $this->component::class,
        );
    }

    protected function renderLayoutViewPersister(): View
    {
        return view('filament.table-layout-toggle.layout-view-persister', [
            'persistEnabled' => true,
            'persistKey' => $this->getKey(),
        ]);
    }

    private function getTableHookNameFromFilamentClassType(): string
    {
        return match (true) {
            is_subclass_of($this->component, ListRecords::class) => 'panels::resource.pages.list-records.table.after',
            is_subclass_of($this->component, ManageRecords::class) => 'panels::resource.pages.list-records.table.after',
            is_subclass_of($this->component, RelationManager::class) => 'panels::resource.relation-manager.after',
            is_subclass_of($this->component, TableWidget::class) => 'widgets::table-widget.start',
            is_subclass_of($this->component, ManageRelatedRecords::class) => 'panels::resource.pages.manage-related-records.table.after',
            default => 'panels::resource.pages.list-records.table.after',
        };
    }
}
