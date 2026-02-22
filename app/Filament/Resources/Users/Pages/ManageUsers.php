<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Resources\Pages\ManageRecords;
use App\Filament\Resources\Users\UserResource;
use Filament\Actions\CreateAction;
use Override;

final class ManageUsers extends ManageRecords
{
    #[Override]
    protected static string $resource = UserResource::class;

    public function getDefaultLayoutView(): string
    {
        return 'grid';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Create User'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Resources\Users\Widgets\UserStatsOverview::class,
        ];
    }
}
