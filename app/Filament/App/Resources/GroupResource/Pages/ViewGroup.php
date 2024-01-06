<?php

namespace App\Filament\App\Resources\GroupResource\Pages;

use App\Filament\App\Resources\GroupResource;
use Filament\Forms;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use App\Models\Teacher;
use App\Models\Session;
use App\Models\Group;
use App\Models\Room;

class ViewGroup extends ViewRecord
{
    protected static string $resource = GroupResource::class;

    protected function getFooterWidgets(): array
    {
        return [
            \App\Filament\App\Widgets\Calendar::make([
                'title' => __('Calendar'),
                'model' => $this->record,
                'creatable' => panel()->auth()->user()->can('create', Session::class),
            ]),
            \App\Filament\App\Widgets\Presence::make([
                'title' => __('Presence'),
                'model' => $this->record,
            ]),
            \App\Filament\App\Widgets\GroupStudents::class,
            \App\Filament\App\Widgets\GroupTeachers::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('create')
                ->label(__('filament-panels::resources/pages/create-record.title', ['label' => strtolower(trans_choice('Session|Sessions', 10))]))
                ->color('gray')
                ->modalWidth('5xl')
                ->visible(panel()->auth()->user()->can('create', Session::class))
                ->form([
                    Forms\Components\Group::make([
                        Forms\Components\Select::make('subject_id')
                            ->label(trans_choice('Subject|Subjects', 1))
                            ->options(function (Group $record) {
                                $record->loadMissing(['branch.subjects' => fn ($q) => $q->wherePivot('year', $record->year)]);
                                return $record->branch->subjects->pluck('name', 'id');
                            })
                            ->afterStateUpdated(fn (Forms\Set $set) => $set('teacher_id', null))
                            ->required()
                            ->live(),
                        Forms\Components\Select::make('teacher_id')
                            ->label(trans_choice('Teacher|Teachers', 1))
                            ->options(function (Forms\Get $get) {
                                return Teacher::whereRelation('subjects', 'id', $get('subject_id'))->active()->pluck('name', 'id');
                            })
                            ->required()
                            ->live(),
                        Forms\Components\Select::make('room_id')
                            ->label(trans_choice('Room|Rooms', 1))
                            ->options(Room::pluck('name', 'id'))
                            ->nullable(),
                        Forms\Components\Select::make('day')
                            ->label(__('Day'))
                            ->options(\App\Enums\Day::class)
                            ->required(),
                    ])
                    ->columns(4),
                    Forms\Components\Group::make([
                        Forms\Components\TimePicker::make('starts_at')
                            ->label(__('Session start time'))
                            ->seconds(false)
                            ->required(),
                        Forms\Components\TimePicker::make('ends_at')
                            ->label(__('Session end time'))
                            ->seconds(false)
                            ->required(),
                        Forms\Components\TimePicker::make('starts_at_ramadan')
                            ->label(__('Session start time (Ramadan)'))
                            ->seconds(false)
                            ->required(),
                        Forms\Components\TimePicker::make('ends_at_ramadan')
                            ->label(__('Session end time (Ramadan)'))
                            ->seconds(false)
                            ->required(),
                    ])
                    ->columns(2),
                    Forms\Components\Toggle::make('show_advanced')
                        ->label(__('Advanced options'))
                        ->columnSpan('full')
                        ->default(false)
                        ->live(),
                    Forms\Components\Group::make([
                        Forms\Components\Select::make('from')
                            ->label(__('Start from'))
                            ->options([
                                '0' => __('Now'),
                                '1' => __('Beginning of season'),
                            ])
                            ->default('0')
                            ->required(),
                        Forms\Components\Select::make('after')
                            ->label(__('First session'))
                            ->options([
                                '0' => __('This week'),
                                '1' => __('After').' 1 '.strtolower(trans_choice('Week|Weeks', 1)),
                                '2' => __('After').' 2 '.strtolower(trans_choice('Week|Weeks', 2)),
                                '3' => __('After').' 3 '.strtolower(trans_choice('Week|Weeks', 3)),
                                '4' => __('After').' 4 '.strtolower(trans_choice('Week|Weeks', 4)),
                            ])
                            ->default('0')
                            ->required(),
                        Forms\Components\Select::make('every')
                            ->label(__('One session'))
                            ->options([
                                '0' => __('This week'),
                                '1' => __('Every').' '.strtolower(trans_choice('Week|Weeks', 1)),
                                '2' => __('Every').' 2 '.strtolower(trans_choice('Week|Weeks', 2)),
                                '3' => __('Every').' 3 '.strtolower(trans_choice('Week|Weeks', 3)),
                                '4' => __('Every').' 4 '.strtolower(trans_choice('Week|Weeks', 4)),
                            ])
                            ->default('1')
                            ->required(),
                    ])
                    ->columns(3)
                    ->visible(fn (Forms\Get $get) => $get('show_advanced')),
                ])
                ->action(function (Group $record, array $data) {
                    match ($data['from'] ?? '0') {
                        default => null,
                        '0' => \App\Support\CreateSessions::createFromNow($record->load('season'), $data),
                        '1' => \App\Support\CreateSessions::createFromBeginning($record->load('season'), $data),
                    };
                    $this->dispatch('calendar-updated');
                    Notification::make()
                        ->title(__('filament-actions::create.single.notifications.created.title'))
                        ->success()
                        ->send();
                }),
            Actions\EditAction::make(),
        ];
    }
}
