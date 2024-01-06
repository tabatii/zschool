<?php

namespace App\Livewire;

use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;
use Phpsa\FilamentPasswordReveal\Password;
use Filament\Notifications\Notification;
use Filament\Facades\Filament;
use Filament\Forms\Form;

class BreezyPassword extends MyProfileComponent
{
    protected string $view = "livewire.breezy-password";

    public ?array $data = [];
    public $user;

    public function mount()
    {
        $this->user = Filament::getCurrentPanel()->auth()->user();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Password::make('current_password')
                    ->label(__('Current password'))
                    ->required()
                    ->rule('current_password'),
                Password::make('new_password')
                    ->label(__('New password'))
                    ->generatable(true)
                    ->passwordUsesSymbols(false)
                    ->required()
                    ->minLength(8)
                    ->confirmed(),
                Password::make('new_password_confirmation')
                    ->label(__('Confirm new password'))
                    ->required(),
            ])
            ->statePath('data');
    }

    public function submit()
    {
        $this->user->update([
            'password' => collect($this->form->getState())->get('new_password')
        ]);
        $this->reset(['data']);
        session()->forget('password_hash_'.Filament::getCurrentPanel()->getAuthGuard());
        Filament::auth()->login($this->user);
        Notification::make()->success()->title(__('filament-breezy::default.profile.password.notify'))->send();
    }
}
