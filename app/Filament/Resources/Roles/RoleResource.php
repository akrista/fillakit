<?php

namespace App\Filament\Resources\Roles;

use App\Filament\Resources\Roles\Pages\ManageRoles;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static ?string $recordTitleAttribute = 'name';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldExclamation;

    protected static ?int $navigationSort = 12;

    public static function getNavigationGroup(): ?string
    {
        return __('menu.nav_group.settings');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Role Details')
                    ->description('Configure the basic details of the role')
                    ->icon(Heroicon::OutlinedShieldExclamation)
                    ->columns(2)
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        TextInput::make('name')
                            ->label('Role')
                            ->required()
                            ->trim()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->placeholder('e.g. admin, editor, user'),
                        TextInput::make('guard_name')
                            ->label('Guard')
                            ->default('web')
                            ->required()
                            ->trim()
                            ->maxLength(255)
                            ->helperText('The authentication guard for this role (see more in the documentation)'),
                        Textarea::make('description')
                            ->columnSpanFull()
                            ->autosize()
                            ->trim()
                            ->maxLength(255)
                            ->helperText('A short description of the role'),
                    ]),
                Section::make('Permissions')
                    ->description('Assign specific permissions to this role')
                    ->icon(Heroicon::OutlinedShieldCheck)
                    ->compact()
                    ->columnSpanFull()
                    ->collapsible()
                    ->schema([
                        CheckboxList::make('permissions')
                            ->searchable()
                            ->required()
                            ->bulkToggleable()
                            ->columns(max(1, ceil(Permission::count() / 8)))
                            ->label('')
                            ->gridDirection('row')
                            ->relationship('permissions', 'name')
                            ->options(Permission::pluck('description', 'id'))
                            ->descriptions(Permission::pluck('name', 'id')),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('Id')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->label('Role')
                    ->weight('font-medium')
                    ->formatStateUsing(fn(string $state): string => Str::headline($state))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('guard_name')
                    ->label('Guard')
                    ->badge()
                    ->color('warning'),
                TextColumn::make('permissions.description')
                    ->label('Permissions')
                    ->searchable()
                    ->badge()
                    ->colors(['success'])
                    ->separator(',')
                    ->limitList(3),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime('d/m/Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),
                ForceDeleteAction::make(),
                RestoreAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageRoles::route('/'),
        ];
    }

    public static function canGloballySearch(): bool
    {
        return false;
    }
}
