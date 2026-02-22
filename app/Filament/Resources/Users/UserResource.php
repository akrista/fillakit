<?php

declare(strict_types=1);

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\ManageUsers;
use App\Models\User;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
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
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Override;

final class UserResource extends Resource
{
    #[Override]
    protected static ?string $model = User::class;

    #[Override]
    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedUserGroup;

    #[Override]
    protected static ?int $navigationSort = 901;

    #[Override]
    protected static ?string $modelLabel = 'Usuario';

    #[Override]
    protected static ?string $pluralModelLabel = 'Usuarios';

    #[Override]
    protected static ?string $recordTitleAttribute = 'email';

    #[Override]
    protected static int $globalSearchResultsLimit = 3;

    public static function getGlobalSearchResultTitle(Model $record): string | Htmlable
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
        $livewire = $table->getLivewire();

        return $table
            ->columns(self::getTableColumns($livewire))
            ->contentGrid(fn (): ?array => $livewire->isGridLayout() ? [
                'md' => 2,
                'lg' => 3,
                'xl' => 3,
            ] : null)
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActionsAlignment('center')
            ->recordActions([
                ViewAction::make()->iconButton(),
                EditAction::make()->iconButton(),
                ActionGroup::make([
                    DeleteAction::make(),
                    Action::make('resend_verification_email')
                        ->label('Resend Email')
                        ->icon(Heroicon::OutlinedEnvelope)
                        ->authorize(fn(User $record): bool => !$record->hasVerifiedEmail())
                        ->action(function (User $record): void {
                            $notification = new VerifyEmail();
                            $notification->url = filament()->getVerifyEmailUrl($record);

                            $record->notify($notification);
                            Notification::make()
                                ->title('Verification email has been resent.')
                                ->send();
                        })
                        ->requiresConfirmation(),
                    ForceDeleteAction::make(),
                    RestoreAction::make(),
                ])->iconButton(),
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

    /**
     * @return array<int, \Filament\Tables\Columns\Column|\Filament\Tables\Columns\Layout\Component>
     */
    private static function getTableColumns(ManageUsers $livewire): array
    {
        $columns = [
            ImageColumn::make('avatar')
                ->circular()
                ->label('Avatar')
                ->imageSize(64)
                ->alignCenter()
                ->verticallyAlignCenter()
                ->getStateUsing(fn (User $record): string => $record->getFilamentAvatarUrl()),
            TextColumn::make('firstname')
                ->label('Name')
                ->formatStateUsing(fn ($record): string => $record->firstname . ' ' . $record->lastname)
                ->searchable(['firstname', 'lastname'])
                ->sortable()
                ->weight('bold')
                ->alignCenter()
                ->verticallyAlignCenter()
                ->limit(30)
                ->tooltip(fn (User $record): string => $record->firstname . ' ' . $record->lastname),
            TextColumn::make('email')
                ->searchable()
                ->sortable()
                ->icon(Heroicon::OutlinedEnvelope)
                ->size('sm')
                ->alignCenter()
                ->verticallyAlignCenter()
                ->limit(30)
                ->tooltip(fn (User $record): string => $record->email),
            TextColumn::make('roles.name')
                ->badge()
                ->separator(',')
                ->alignCenter()
                ->verticallyAlignCenter()
                ->limitList(3),
        ];

        if ($livewire->isGridLayout()) {
            return [
                Stack::make([
                    $columns[0]->alignCenter()->verticallyAlignCenter(),
                    $columns[1]->alignCenter()->verticallyAlignCenter(),
                    $columns[2]->alignCenter()->verticallyAlignCenter(),
                    $columns[3]->alignCenter()->verticallyAlignCenter(),
                ])->space(3),
            ];
        }

        return $columns;
    }
}
