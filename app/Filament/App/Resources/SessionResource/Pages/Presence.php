<?php

namespace App\Filament\App\Resources\SessionResource\Pages;

use App\Filament\App\Resources\GroupResource;
use App\Filament\App\Resources\SessionResource;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;
use Filament\Forms\Form;
use Filament\Forms;
use App\Models\Group;

class Presence extends Page implements HasForms
{
    use InteractsWithForms;

    protected static string $resource = SessionResource::class;

    protected static string $view = 'filament.app.resources.session-resource.pages.presence';

    public ?bool $is_active;

    public function mount()
    {
        $this->form->fill();
    }

    public function getTitle(): string
    {
        return __('Presence');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Toggle::make('is_active')
                            ->label(__('Only active groups'))
                            ->default(true)
                            ->live()
                    ])
                    ->columns(1),
            ]);
    }

    protected function getFooterWidgets(): array
    {
        return GroupResource::getEloquentQuery()
            ->when($this->is_active, fn ($query) => $query->whereHas('season', fn ($q) => $q->active()))
            ->get()
            ->map(fn (Group $group) => \App\Filament\App\Widgets\Presence::make([
                'title' => $group->name,
                'model' => $group,
            ]))
            ->all();
    }
}
