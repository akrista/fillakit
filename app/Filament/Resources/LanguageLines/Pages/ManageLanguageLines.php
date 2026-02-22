<?php

declare(strict_types=1);

namespace App\Filament\Resources\LanguageLines\Pages;

use App\Filament\Resources\LanguageLines\LanguageLineResource;
use App\Filament\Resources\Pages\ManageRecords;
use Filament\Actions\CreateAction;
use Override;

final class ManageLanguageLines extends ManageRecords
{
    #[Override]
    protected static string $resource = LanguageLineResource::class;

    public function getDefaultLayoutView(): string
    {
        return 'grid';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
