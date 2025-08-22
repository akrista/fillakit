<?php

namespace App\Filament\Pages\Auth;

use Filament\Schemas\Schema;

class Login extends \Filament\Auth\Pages\Login
{
    public function mount(): void
    {
        parent::mount();

        if (config('app.env') !== 'production') {
            $this->form->fill([
                'email' => config('filamentry.admin_email'),
                'password' => config('filamentry.admin_password'),
            ]);
        }
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getEmailFormComponent(),
                $this->getPasswordFormComponent(),
                $this->getRememberFormComponent(),
            ]);
    }
}
