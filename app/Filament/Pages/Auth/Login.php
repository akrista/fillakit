<?php

declare(strict_types=1);

namespace App\Filament\Pages\Auth;

use Filament\Schemas\Schema;

final class Login extends \Filament\Auth\Pages\Login
{
    public function mount(): void
    {
        parent::mount();

        $defaults = ['remember' => true];

        if (config('app.env') !== 'production') {
            $defaults['email'] = config('fillakit.admin_email');
            $defaults['password'] = config('fillakit.admin_password');
        }

        $this->form->fill($defaults);
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
