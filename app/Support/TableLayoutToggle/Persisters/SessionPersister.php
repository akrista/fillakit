<?php

declare(strict_types=1);

namespace App\Support\TableLayoutToggle\Persisters;

use App\Support\TableLayoutToggle\Contracts\LayoutPersister;

final class SessionPersister extends AbstractPersister implements LayoutPersister
{
    public function setState(string $layoutState): self
    {
        session()->put($this->getKey(), $layoutState);

        return $this;
    }

    public function getState(): ?string
    {
        return session()->get($this->getKey());
    }

    protected function renderLayoutViewPersister(): \Illuminate\Contracts\View\View
    {
        return \Illuminate\Support\Facades\View::make('filament.table-layout-toggle.layout-view-persister', [
            'persistEnabled' => false,
            'persistKey' => $this->getKey(),
        ]);
    }
}
