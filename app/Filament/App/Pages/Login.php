<?php

namespace App\Filament\App\Pages;

use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Login as BaseLogin;

class Login extends BaseLogin
{
    public function getTitle(): string | Htmlable
    {
        return match (panel()->getId()) {
            'admin' => 'Administration Login',
            'teacher' => 'Teacher Login',
            'student' => 'Student Login',
            'guardian' => 'Parent Login',
        };
    }

    public function getHeading(): string | Htmlable
    {
        return match (panel()->getId()) {
            'admin' => 'Administration Login',
            'teacher' => 'Teacher Login',
            'student' => 'Student Login',
            'guardian' => 'Parent Login',
        };
    }

    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getUsernameFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getRememberFormComponent(),
                    ])
                    ->statePath('data'),
            ),
        ];
    }

    protected function getUsernameFormComponent(): Component
    {
        return TextInput::make('username')
            ->label(__('Username'))
            ->required()
            ->autocomplete()
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    protected function getCredentialsFromFormData(array $data): array
    {
        return [
            'username' => $data['username'],
            'password' => $data['password'],
        ];
    }

    protected function throwFailureValidationException(): never
    {
        throw ValidationException::withMessages([
            'data.username' => __('filament-panels::pages/auth/login.messages.failed'),
        ]);
    }
}
