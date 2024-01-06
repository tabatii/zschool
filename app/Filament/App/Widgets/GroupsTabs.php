<?php

namespace App\Filament\App\Widgets;

use Illuminate\Support\Collection;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use Filament\Widgets\Widget;

class GroupsTabs extends Widget
{
    protected static string $view = 'filament.app.widgets.groups-tabs';

    protected int | string | array $columnSpan = 'full';

    protected static bool $isDiscovered = false;

    protected static bool $isLazy = false;

    #[Locked]
    public Collection $groups;

    public ?int $active;

    #[On('group-changed')]
    public function changeGroup(int $group_id)
    {
        $this->active = $group_id;
    }
}
