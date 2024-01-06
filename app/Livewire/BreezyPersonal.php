<?php

namespace App\Livewire;

use Jeffgreco13\FilamentBreezy\Livewire\MyProfileComponent;
use Filament\Notifications\Notification;
use Filament\Facades\Filament;
use Filament\Forms\Form;
use Filament\Forms;

class BreezyPersonal extends MyProfileComponent
{
    protected string $view = "livewire.breezy-personal";
    protected array $only = [
        'avatar',
        'gender',
        'name',
        'username',
        'mobile',
        'email',
        'birthday',
        'address',
    ];

    public ?array $data;
    public $user;

    public function mount()
    {
        $this->user = Filament::getCurrentPanel()->auth()->user();
        $this->form->fill($this->user->only($this->only));
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\FileUpload::make('avatar')
                    ->label(__('Photo'))
                    ->columnSpan(['default' => 3, 'xl' => 1])
                    ->directory(now()->format('d-m-Y'))
                    ->avatar()
                    ->nullable()
                    ->maxSize(2048),
                Forms\Components\Group::make([
                    Forms\Components\Select::make('gender')
                        ->label(__('Gender'))
                        ->options(\App\Enums\Gender::class)
                        ->required(),
                    Forms\Components\TextInput::make('name')
                        ->label(__('Full name'))
                        ->required()
                        ->maxLength(255),
                ])
                ->columnSpan(['default' => 3, 'xl' => 2]),
                Forms\Components\Group::make([
                    Forms\Components\TextInput::make('username')
                        ->label(__('Username'))
                        ->required()
                        ->maxLength(255),
                    Forms\Components\TextInput::make('mobile')
                        ->label(__('Phone number'))
                        ->nullable()
                        ->tel()
                        ->unique(ignoreRecord: true),
                    Forms\Components\TextInput::make('email')
                        ->label(__('Email'))
                        ->nullable()
                        ->email()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),
                    Forms\Components\DatePicker::make('birthday')
                        ->label(__('Birthday'))
                        ->nullable(),
                    Forms\Components\Textarea::make('address')
                        ->label(__('Address'))
                        ->rows(3)
                        ->nullable(),
                ])
                ->columnSpan('full'),
            ])
            ->columns(['default' => 3])
            ->model($this->user)
            ->statePath('data');
    }

    public function submit()
    {
        $this->user->update(collect($this->form->getState())->only($this->only)->all());
        Notification::make()->success()->title(__('filament-breezy::default.profile.personal_info.notify'))->send();
    }
}
