<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Schema;

final class Register extends \Filament\Auth\Pages\Register
{
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getUsernameFormComponent(),
                $this->getFirstnameFormComponent(),
                $this->getLastnameFormComponent(),
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getPasswordConfirmationFormComponent(),
            ]);
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label(__('filament-panels::auth/pages/register.form.email.label'))
            ->email()
            ->required()
            ->maxLength(255)
            ->unique($this->getUserModel())
            ->validationMessages([
                'unique' => __('This email is already registered.'),
            ]);
    }

    private function getUsernameFormComponent(): Component
    {
        return TextInput::make('username')
            ->label(__('Username'))
            ->required()
            ->maxLength(255)
            ->unique($this->getUserModel())
            ->validationMessages([
                'unique' => __('This username is already taken.'),
            ])
            ->autofocus()
            ->autocomplete(false);
    }

    private function getFirstnameFormComponent(): Component
    {
        return TextInput::make('firstname')
            ->label(__('First Name'))
            ->required()
            ->maxLength(255);
    }

    private function getLastnameFormComponent(): Component
    {
        return TextInput::make('lastname')
            ->label(__('Last Name'))
            ->required()
            ->maxLength(255);
    }
}
