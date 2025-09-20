<?php

declare(strict_types=1);

namespace App\Filament\Resources\Permissions\Pages;

use App\Filament\Resources\Permissions\PermissionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

final class ManagePermissions extends ManageRecords
{
    protected static string $resource = PermissionResource::class;

    public function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create Permission'),
        ];
    }
}
