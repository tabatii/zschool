<?php

namespace App\Filament\App\Widgets;

use Illuminate\Contracts\View\View;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Action;
use Filament\Widgets\Widget;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use App\Traits\CalendarControls;
use App\Traits\SessionTime;
use App\Models\Session;
use App\Models\Group;
use Carbon\Carbon;

class Presence extends Widget implements HasActions, HasForms
{
    use CalendarControls, SessionTime, InteractsWithActions, InteractsWithForms;

    protected static string $view = 'filament.app.widgets.presence';

    protected int | string | array $columnSpan = 'full';

    protected static bool $isDiscovered = false;

    protected static bool $isLazy = false;

    #[Locked]
    public ?Group $model;

    #[Locked]
    public ?string $title;

    #[Locked]
    public bool $hideSunday = false;

    public function mount()
    {
        $this->now = Carbon::now();
        $this->week = $this->now->format('Y-\\WW');
        $this->setWeekRange();
    }

    #[On('calendar-updated')]
    public function refresh() {}

    #[On('group-changed')]
    public function changeGroup(int $group_id)
    {
        $this->model = Group::find($group_id);
    }

    #[Computed]
    public function group()
    {
        if (isset($this->model)) {
            $group = $this->model->load([
                'students' => fn ($q) => $q->select('id', 'name')->orderBy('name')->where(fn ($q2) => match (panel()->getId()) {
                    default => $q2,
                    'student' => $q2->where('id', panel()->auth()->id()),
                    'guardian' => $q2->where('id', tenant()->getKey()),
                })->withCount([
                    'absenceSessions' => fn ($q2) => $q2->forModel(panel()->auth()->user())->where('group_id', $this->model->id),
                ]),
                'sessions' => fn ($q) => $q->with([
                    'group.season', 'subject', 'teacher', 'room', 'students:id',
                ])
                ->forModel(panel()->auth()->user())
                ->whereBetween('starts_at', [$this->startOfWeek, $this->endOfWeek]),
            ]);
            $group->sessions->map(function (Session $session) {
                $session->starts_at_date = $this->getStartsAtForSession($session, $session->group->season);
                $session->ends_at_date = $this->getEndsAtForSession($session, $session->group->season);
                $session->width_styles = \Illuminate\Support\Arr::toCssStyles([
                    'width:' . ($session->starts_at_date->diffInMinutes($session->ends_at_date) / 5) . 'px',
                ]);
                return $session;
            });
            return $group;
        }
    }

    public function openAction(): Action
    {
        return Action::make('open')
            ->modalHeading(' ')
            ->modalContent(function (array $arguments): View {
                $session = $this->group->sessions->firstWhere('id', $arguments['session_id']);
                return view('components.app.presence-modal', [
                    'session' => $session,
                    'group' => $this->group,
                    'student' => $this->group->students->firstWhere('id', $arguments['student_id']),
                    'pivot' => $session->students->firstWhere('id', $arguments['student_id'])?->pivot,
                ]);
            })
            ->modalCancelAction(false)
            ->modalSubmitAction(false)
            ->modalWidth('sm');
    }
}
