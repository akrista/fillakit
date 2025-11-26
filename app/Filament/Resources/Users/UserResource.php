<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\ManageUsers;
use App\Models\User;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Actions\ViewAction;
use Filament\Auth\Notifications\VerifyEmail;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

final class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?int $navigationSort = 11;

    protected static int $globalSearchResultsLimit = 3;

    protected static ?string $recordTitleAttribute = 'email';

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->email;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['firstname', 'lastname', 'email', 'username'];
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Username' => $record->username,
            'Name' => $record->firstname . ' ' . $record->lastname,
        ];
    }

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
                        Tab::make('Personal Information')
                            ->icon(Heroicon::OutlinedUser)
                            ->schema([
                                Section::make('')
                                    ->description('Basic user data')
                                    ->icon(Heroicon::OutlinedUser)
                                    ->collapsible()
                                    ->columnSpanFull()
                                    ->columns(2)
                                    ->schema([
                                        SpatieMediaLibraryFileUpload::make('avatar_url')
                                            ->label('Avatar URL')
                                            ->collection('avatars')
                                            ->disk('public')
                                            ->avatar()
                                            ->required()
                                            ->columnSpanFull(),
                                        TextInput::make('id')
                                            ->hidden(fn(string $operation): bool => $operation !== 'view'),
                                        TextInput::make('firstname')
                                            ->label('Firstname')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('lastname')
                                            ->label('Lastname')
                                            ->required()
                                            ->maxLength(255),
                                        TextInput::make('username')
                                            ->label('Username')
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(User::class, 'username', ignoreRecord: true),
                                        TextInput::make('email')
                                            ->label('Email')
                                            ->email()
                                            ->required()
                                            ->maxLength(255)
                                            ->unique(User::class, 'email', ignoreRecord: true),
                                        DatePicker::make('email_verified_at')
                                            ->label('Verified at')
                                            ->displayFormat('d/m/Y h:i A')
                                            ->readOnly(fn(string $operation, $state): bool => $operation === 'edit' && !is_null($state))
                                            ->native(false),
                                        TextInput::make('password')
                                            ->label('Password')
                                            ->password()
                                            ->revealable()
                                            ->minLength(8)
                                            ->dehydrated(filled(...))
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
                        Tab::make('Audit Information')
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
                SpatieMediaLibraryImageColumn::make('avatar_url')
                    ->circular()
                    ->label('Avatar')
                    ->collection('avatars')
                    ->disk('public')
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('username')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('firstname')
                    ->label('Name')
                    ->formatStateUsing(fn($record): string => $record->firstname . ' ' . $record->lastname)
                    ->searchable(['firstname', 'lastname'])
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: false),
                TextColumn::make('email')
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('roles.name')
                    ->toggleable(isToggledHiddenByDefault: false)
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
                Action::make('resend_verification_email')
                    ->label('Resend Email')
                    ->icon(Heroicon::OutlinedEnvelope)
                    ->authorize(fn(User $record): bool => !$record->hasVerifiedEmail())
                    ->action(function (User $record): void {
                        $notification = new VerifyEmail;
                        $notification->url = filament()->getVerifyEmailUrl($record);

                        $record->notify($notification);
                        Notification::make()
                            ->title('Verification email has been resent.')
                            ->send();
                    })
                    ->requiresConfirmation(),
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
