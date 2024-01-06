<?php

namespace App\Filament\App\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use Livewire\Attributes\Computed;

class Dashboard extends BaseDashboard
{
    protected function getHeaderWidgets(): array
    {
        return array_values(array_filter([
            match (panel()->getId()) {
                default => null,
                'admin' => \App\Filament\App\Widgets\GroupsTabs::make([
                    'groups' => $this->groups,
                    'active' => $this->group?->getKey(),
                ]),
            },
            \App\Filament\App\Widgets\Calendar::make([
                'title' => __('Calendar'),
                'creatable' => panel()->auth()->user()->can('create', \App\Models\Session::class),
                'model' => match (panel()->getId()) {
                    default => null,
                    'admin' => $this->group,
                    'teacher' => panel()->auth()->user(),
                    'student' => panel()->auth()->user(),
                    'guardian' => tenant(),
                }
            ]),
        ]));
    }

    #[Computed]
    public function groups()
    {
        return \App\Filament\App\Resources\GroupResource::getEloquentQuery()->whereHas('season', fn ($q) => $q->active())->get();
    }

    #[Computed]
    public function group()
    {
        return $this->groups->first();
    }
}
