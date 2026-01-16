<?php

declare(strict_types=1);

namespace App\Filament\Resources\LanguageLines;

use App\Filament\Resources\LanguageLines\Pages\ManageLanguageLines;
use App\Models\LanguageLine;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

final class LanguageLineResource extends Resource
{
    protected static ?string $model = LanguageLine::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLanguage;

    protected static ?string $recordTitleAttribute = 'key';

    protected static ?string $modelLabel = 'Translation';

    protected static ?string $pluralModelLabel = 'Translations';

    public static function getNavigationGroup(): ?string
    {
        return __('menu.nav_group.settings');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('group')
                    ->label('Group')
                    ->required()
                    ->placeholder('validation, auth, messages...'),
                TextInput::make('key')
                    ->label('Key')
                    ->required()
                    ->placeholder('required, email, password...'),
                KeyValue::make('text')
                    ->label('Translation')
                    ->keyLabel('Language')
                    ->valueLabel('Text')
                    ->keyPlaceholder('en, es, pt...')
                    ->valuePlaceholder('Translation text')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->paginated([12, 24, 48, 'all'])
            ->recordTitleAttribute('key')
            ->columns([
                Stack::make([
                    Split::make([
                        TextColumn::make('group')
                            ->badge()
                            ->color('primary')
                            ->searchable()
                            ->sortable()
                            ->grow(false),
                        TextColumn::make('key')
                            ->weight(FontWeight::Bold)
                            ->searchable()
                            ->sortable()
                            ->limit(50),
                    ]),
                    TextColumn::make('text')
                        ->formatStateUsing(function ($state): string {
                            if (is_array($state)) {
                                return collect($state)
                                    ->map(fn ($value, string $key): string => sprintf('%s: %s', $key, $value))
                                    ->implode(' | ');
                            }

                            return (string) $state;
                        })
                        ->color('gray')
                        ->limit(100)
                        ->wrap(),
                ])->space(2),
            ])
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->filters([
                SelectFilter::make('group')
                    ->label('Group')
                    ->options(fn () => LanguageLine::query()
                        ->distinct()
                        ->pluck('group', 'group')
                        ->toArray()),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageLanguageLines::route('/'),
        ];
    }
}
