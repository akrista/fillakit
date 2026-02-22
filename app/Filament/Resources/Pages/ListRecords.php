<?php

declare(strict_types=1);

namespace App\Filament\Resources\Pages;

use App\Support\TableLayoutToggle\Concerns\HasToggleableTable;
use Filament\Resources\Pages\ListRecords as FilamentListRecords;

abstract class ListRecords extends FilamentListRecords
{
    use HasToggleableTable;
}
