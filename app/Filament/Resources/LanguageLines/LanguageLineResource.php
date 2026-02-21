<?php

declare(strict_types=1);

namespace App\Filament\Resources\LanguageLines;

use App\Filament\Resources\LanguageLines\Pages\ManageLanguageLines;
use App\Models\LanguageLine;
use BackedEnum;
use Filament\Actions\ActionGroup;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Override;

final class LanguageLineResource extends Resource
{
    #[Override]
    protected static ?string $model = LanguageLine::class;

    #[Override]
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLanguage;

    #[Override]
    protected static ?string $recordTitleAttribute = 'key';

    #[Override]
    protected static ?int $navigationSort = 1003;

    #[Override]
    protected static ?string $modelLabel = 'Traducción';

    #[Override]
    protected static ?string $pluralModelLabel = 'Traducciones';

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
        $table->getLivewire();

        return $table
            ->recordTitleAttribute('key')
            ->columns(self::getTableColumns())
            ->filters([
                SelectFilter::make('group')
                    ->label('Group')
                    ->options(fn () => LanguageLine::query()
                        ->distinct()
                        ->pluck('group', 'group')
                        ->toArray()),
            ])
            ->recordActionsAlignment('center')
            ->recordActions([
                ViewAction::make()->iconButton(),
                EditAction::make()->iconButton(),
                ActionGroup::make([
                    DeleteAction::make(),
                ])->iconButton(),
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

    /**
     * @return array<int, \Filament\Tables\Columns\Column|\Filament\Tables\Columns\Layout\Component>
     */
    private static function getTableColumns(): array
    {
        return [
            TextColumn::make('group')
                ->badge()
                ->limit(30)
                ->color('primary')
                ->searchable()
                ->sortable(),
            TextColumn::make('key')
                ->weight(FontWeight::Bold)
                ->searchable()
                ->sortable()
                ->limit(30)
                ->tooltip(fn ($record): string => $record->key),
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
                ->size('sm')
                ->limit(60)
                ->tooltip(fn ($record): ?string => is_array($record->text)
                    ? collect($record->text)->map(fn ($v, string $k): string => sprintf('%s: %s', $k, $v))->implode(' | ')
                    : $record->text),
        ];
    }
}
