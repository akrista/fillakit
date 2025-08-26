<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\ManageUsers;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    protected static ?int $navigationSort = 11;

    public static function getNavigationGroup(): ?string
    {
        return __('menu.nav_group.settings');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make('User')
                    ->columnSpanFull()
                    ->tabs([
                        Tabs\Tab::make('Personal Information')
                            ->icon(Heroicon::OutlinedUser)
                            ->schema([
                                Section::make('')
                                    ->description('Basic user data')
                                    ->icon(Heroicon::OutlinedUser)
                                    ->collapsible()
                                    ->columnSpanFull()
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('avatar_url')
                                            ->label('Avatar URL')
                                            ->url()
                                            ->columnSpanFull()
                                            ->maxLength(255),
                                        TextInput::make('name')
                                            ->label('Name')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('id')
                                            ->hidden(fn(string $operation): bool => $operation !== 'view'),
                                        TextInput::make('email')
                                            ->label('Email')
                                            ->email()
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(),
                                        DatePicker::make('email_verified_at')
                                            ->label('Verified at')
                                            ->displayFormat('d/m/Y h:i A')
                                            ->native(false)
                                            ->hidden(fn(string $operation): bool => $operation !== 'view'),
                                        TextInput::make('password')
                                            ->label('Password')
                                            ->password()
                                            ->revealable()
                                            ->minLength(8)
                                            ->dehydrated(fn(?string $state): bool => filled($state))
                                            ->required(fn(string $operation): bool => $operation === 'create')
                                            ->hidden(fn(string $operation): bool => $operation === 'view'),
                                        Select::make('roles')
                                            ->label('Roles')
                                            ->required()
                                            ->columnSpanFull()
                                            ->multiple()
                                            ->preload()
                                            ->searchable()
                                            ->relationship('roles', 'name')
                                            ->helperText('Select the roles this user will have'),
                                    ]),
                            ]),
                        Tabs\Tab::make('Audit Information')
                            ->icon(Heroicon::OutlinedClock)
                            ->hidden(fn(string $operation): bool => $operation !== 'view')
                            ->schema([
                                Section::make('')
                                    ->description('Information about the user creation, update and deletion')
                                    ->icon(Heroicon::OutlinedClock)
                                    ->collapsible()
                                    ->columnSpanFull()
                                    ->columns(2)
                                    ->hidden(fn(string $operation): bool => $operation !== 'view')
                                    ->schema([
                                        TextInput::make('created_by.name')
                                            ->label('Created by')
                                            ->hidden(fn(string $operation): bool => $operation !== 'view'),
                                        DatePicker::make('created_at')
                                            ->displayFormat('d/m/Y h:i A')
                                            ->hidden(fn(string $operation): bool => $operation !== 'view'),
                                        TextInput::make('updated_by.name')
                                            ->label('Updated by')
                                            ->hidden(fn(string $operation): bool => $operation !== 'view'),
                                        DatePicker::make('updated_at')
                                            ->displayFormat('d/m/Y h:i A')
                                            ->hidden(fn(string $operation): bool => $operation !== 'view'),
                                        TextInput::make('deleted_by.name')
                                            ->label('Deleted by')
                                            ->hidden(fn(string $operation): bool => $operation !== 'view'),
                                        DatePicker::make('deleted_at')
                                            ->displayFormat('d/m/Y h:i A')
                                            ->hidden(fn(string $operation): bool => $operation !== 'view'),
                                    ]),
                            ]),
                    ]),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('roles.name')
                    ->badge()
                    ->separator(',')
                    ->limitList(3),
                TextColumn::make('email_verified_at')
                    ->label('Verified at')
                    ->dateTime('d/m/Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->dateTime('d/m/Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime('d/m/Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime('d/m/Y h:i A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
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

    public static function getPages(): array
    {
        return [
            'index' => ManageUsers::route('/'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
