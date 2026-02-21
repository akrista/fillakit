<?php

declare(strict_types=1);

namespace App\Filament\Resources\LanguageLines\Pages;

use App\Filament\Resources\LanguageLines\LanguageLineResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Override;

final class ManageLanguageLines extends ManageRecords
{
    #[Override]
    protected static string $resource = LanguageLineResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
