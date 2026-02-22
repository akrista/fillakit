<?php

declare(strict_types=1);

namespace App\Support\TableLayoutToggle\Contracts;

use Livewire\Component;

interface LayoutPersister
{
    public function __construct(Component $component);

    public function getKey(): string;

    public function setKey(string $key): self;

    public function defaultKey(): string;

    public function getState(): ?string;

    public function setState(string $layoutState): self;

    public function onComponentBoot(): void;

    public function onComponentBooted(): void;
}
