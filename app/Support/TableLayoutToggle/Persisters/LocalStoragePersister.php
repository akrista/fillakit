<?php

declare(strict_types=1);

namespace App\Support\TableLayoutToggle\Persisters;

use App\Support\TableLayoutToggle\Contracts\LayoutPersister;

final class LocalStoragePersister extends AbstractPersister implements LayoutPersister
{
    public function setState(string $layoutState): self
    {
        return $this;
    }

    public function getState(): ?string
    {
        return null;
    }
}
