<?php

namespace App\Filament\App\Widgets;

use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\Action;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms;
use Filament\Widgets\Widget;
use Filament\Notifications\Notification;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Attributes\On;
use App\Traits\CalendarControls;
use App\Traits\SessionTime;
use App\Models\Session;
use App\Models\Teacher;
use App\Models\Group;
use App\Models\Topic;
use App\Models\Room;
use App\Models\Exam;
use Carbon\Carbon;

class Calendar extends Widget implements HasActions, HasForms
{
    use CalendarControls, SessionTime, InteractsWithActions, InteractsWithForms;

    protected static string $view = 'filament.app.widgets.calendar';

    protected int | string | array $columnSpan = 'full';

    protected static bool $isDiscovered = false;

    protected static bool $isLazy = false;

    #[Locked]
    public ?Model $model;

    #[Locked]
    public ?string $title;
    
    public ?string $day = null;
    public ?int $starts_at = null;
    public ?int $ends_at = null;

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
        $this->dispatch('calendar-changed');
    }

    #[Computed]
    public function sessions()
    {
        if (isset($this->model)) {
            return Session::with(['group.season', 'subject', 'teacher', 'room'])
                ->whereHas('group', fn ($q) => $q->whereHas('season', fn ($q2) => $q2->active()))
                ->whereBetween('starts_at', [$this->startOfWeek, $this->endOfWeek])
                ->forModel($this->model)
                ->get()
                ->map(function ($session) {
                    $session->starts_at_date = $this->getStartsAtForSession($session, $session->group->season);
                    $session->ends_at_date = $this->getEndsAtForSession($session, $session->group->season);
                    return $session;
                });
        }
        return collect([]);
    }

    #[Computed]
    public function rooms()
    {
        return Room::pluck('name', 'id');
    }

    public function examAction(): Action
    {
        return Action::make('exam')
            ->label(trans_choice('Exam|Exams', 1))
            ->icon('heroicon-o-document-plus')
            ->color('gray')
            ->fillForm(function (array $arguments): array {
                $exam = Exam::with('topics')->where('session_id', $arguments['session_id'])->first();
                return is_null($exam) ? ['subject_id' => $arguments['subject_id']] : [
                    'subject_id' => $arguments['subject_id'],
                    'description' => $exam->description,
                    'topics' => $exam->topics->pluck('id'),
                ];
            })
            ->form([
                Forms\Components\Hidden::make('subject_id'),
                Forms\Components\Select::make('topics')
                    ->label(trans_choice('Topic|Topics', 10))
                    ->options(fn (Forms\Get $get) => Topic::where('subject_id', $get('subject_id'))->oldest('id')->pluck('name', 'id'))
                    ->multiple()
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label(__('Description'))
                    ->rows(3)
                    ->nullable(),
            ])
            ->action(function (array $arguments, array $data) {
                abort_unless(panel()->auth()->user()->can('update', $session = $this->sessions->sole('id', $arguments['session_id'])), 404);
                $exam = Exam::updateOrCreate(['session_id' => $session->id], Arr::only($data, ['description']));
                $session->loadMissing('students:id');
                $exam->topics()->sync($data['topics']);
                $exam->students()->sync($session->students->pluck('id'));
                $this->dispatch('calendar-updated');
                Notification::make()
                    ->title(__('filament-actions::edit.single.notifications.saved.title'))
                    ->success()
                    ->send();
            });
    }

    public function editAction(): Action
    {
        return Action::make('edit')
            ->label(__('filament-actions::edit.single.label'))
            ->icon('heroicon-o-pencil-square')
            ->color('primary')
            ->modalWidth('5xl')
            ->fillForm(fn (array $arguments): array => $this->sessions->sole('id', $arguments['session_id'])->only([
                'subject_id', 'teacher_id', 'room_id', 'starts_at', 'ends_at', 'starts_at_ramadan', 'ends_at_ramadan',
            ]))
            ->form([
                Forms\Components\Group::make([
                    Forms\Components\Select::make('subject_id')
                        ->label(trans_choice('Subject|Subjects', 1))
                        ->visible(fn (): bool => panel()->getId() === 'admin')
                        ->options(function () {
                            $this->model->loadMissing(['branch.subjects' => fn ($q) => $q->wherePivot('year', $this->model->year)]);
                            return $this->model->branch->subjects->pluck('name', 'id');
                        })
                        ->afterStateUpdated(fn (Forms\Set $set) => $set('teacher_id', null))
                        ->required()
                        ->live(),
                    Forms\Components\Select::make('teacher_id')
                        ->label(trans_choice('Teacher|Teachers', 1))
                        ->visible(fn (): bool => panel()->getId() === 'admin')
                        ->options(function (Forms\Get $get) {
                            return Teacher::whereRelation('subjects', 'id', $get('subject_id'))->active()->pluck('name', 'id');
                        })
                        ->required()
                        ->live(),
                    Forms\Components\Select::make('room_id')
                        ->label(trans_choice('Room|Rooms', 1))
                        ->options($this->rooms)
                        ->nullable(),
                ])
                ->columns(3),
                Forms\Components\Group::make([
                    Forms\Components\DateTimePicker::make('starts_at')
                        ->label(__('Session start time'))
                        ->seconds(false)
                        ->required(),
                    Forms\Components\DateTimePicker::make('ends_at')
                        ->label(__('Session end time'))
                        ->seconds(false)
                        ->required()
                        ->after('starts_at'),
                    Forms\Components\DateTimePicker::make('starts_at_ramadan')
                        ->label(__('Session start time (Ramadan)'))
                        ->seconds(false)
                        ->nullable(),
                    Forms\Components\DateTimePicker::make('ends_at_ramadan')
                        ->label(__('Session end time (Ramadan)'))
                        ->seconds(false)
                        ->nullable()
                        ->after('starts_at_ramadan'),
                ])
                ->columns(2)
            ])
            ->action(function (array $arguments, array $data) {
                abort_unless(panel()->auth()->user()->can('update', $session = $this->sessions->sole('id', $arguments['session_id'])), 404);
                Session::where('id', $session->id)->update($data);
                $session->group->teachers()->sync(Session::where('group_id', $session->group_id)->pluck('teacher_id'));
                $this->dispatch('calendar-updated');
                Notification::make()
                    ->title(__('filament-actions::edit.single.notifications.saved.title'))
                    ->success()
                    ->send();
            });
    }

    public function deleteAction(): Action
    {
        return Action::make('delete')
            ->label(__('filament-actions::delete.single.label'))
            ->requiresConfirmation()
            ->modalIcon('heroicon-o-trash')
            ->icon('heroicon-o-trash')
            ->color('danger')
            ->action(function (array $arguments) {
                abort_unless(panel()->auth()->user()->can('delete', $session = $this->sessions->sole('id', $arguments['session_id'])), 404);
                $session->delete();
                $session->group->teachers()->sync(Session::where('group_id', $session->group_id)->pluck('teacher_id'));
                $this->dispatch('calendar-updated');
                Notification::make()
                    ->title(__('filament-actions::delete.single.notifications.deleted.title'))
                    ->success()
                    ->send();
            });
    }

    public function bulkDeleteAction(): Action
    {
        return Action::make('bulkDelete')
            ->label(__('Collective deletion'))
            ->requiresConfirmation()
            ->modalIcon('heroicon-o-trash')
            ->icon('heroicon-o-trash')
            ->color('danger')
            ->action(function (array $arguments) {
                abort_unless(panel()->auth()->user()->can('delete', $session = $this->sessions->sole('id', $arguments['session_id'])), 404);
                Session::where('mutual_id', $session->mutual_id)->where('starts_at', '>', now())->delete();
                $session->group->teachers()->sync(Session::where('group_id', $session->group_id)->pluck('teacher_id'));
                $this->dispatch('calendar-updated');
                Notification::make()
                    ->title(__('filament-actions::delete.single.notifications.deleted.title'))
                    ->success()
                    ->send();
            });
    }
}
