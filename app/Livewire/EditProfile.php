<?php

declare(strict_types=1);

namespace App\Livewire;

use Exception;
use Filament\Facades\Filament;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;
use Filament\Support\Facades\FilamentView;

use function Filament\Support\is_app_url;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;
use Throwable;

final class EditProfile extends MyProfileComponent
{
    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public $user;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                SpatieMediaLibraryFileUpload::make('media')->label('Avatar')
                    ->collection('avatars')
                    ->disk('public')
                    ->avatar(),
                Grid::make()->schema([
                    TextInput::make('username')
                        ->required()
                        ->unique('users', 'username', ignoreRecord: true)
                        ->minLength(3)
                        ->maxLength(50)
                        ->regex('/^w+$/'),
                    TextInput::make('email')
                        ->email()
                        ->required()
                        ->unique('users', 'email', ignoreRecord: true)
                        ->maxLength(255),
                ]),
                Grid::make()->schema([
                    TextInput::make('firstname')
                        ->required(),
                    TextInput::make('lastname')
                        ->required(),
                ]),
            ])
            ->operation('edit')
            ->model($this->getUser())
            ->statePath('data');
    }

    public function mount(): void
    {
        $this->fillForm();
    }

    public function getUser(): Authenticatable&Model
    {
        $user = Filament::auth()->user();

        if (! $user instanceof Model) {
            throw new Exception('The authenticated user object must be an Eloquent model to allow the profile page to update it.');
        }

        return $user;
    }

    public function submit(): void
    {
        try {
            $data = $this->form->getState();

            $this->handleRecordUpdate($this->getUser(), $data);

            Notification::make()
                ->title('Profile updated')
                ->success()
                ->send();

            $this->redirect('profile', navigate: FilamentView::hasSpaMode() && is_app_url('profile'));
        } catch (Throwable $throwable) {
            Notification::make()
                ->title('Failed to update.')
                ->danger()
                ->send();
        }
    }

    public function render(): View
    {
        return view('livewire.auth.edit-profile');
    }

    private function fillForm(): void
    {
        $data = $this->getUser()->attributesToArray();

        $this->form->fill($data);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    private function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update($data);

        return $record;
    }
}
