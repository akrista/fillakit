<?php

declare(strict_types=1);

namespace App\Filament\Resources\Pages;

use App\Support\TableLayoutToggle\Concerns\HasToggleableTable;
use Filament\Resources\Pages\ManageRecords as FilamentManageRecords;

abstract class ManageRecords extends FilamentManageRecords
{
    use HasToggleableTable;
}
