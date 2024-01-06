<?php

namespace App\Filament\App\Resources\ExamResource\Pages;

use App\Filament\App\Resources\GroupResource;
use App\Filament\App\Resources\ExamResource;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Get;
use Filament\Resources\Pages\ListRecords;
use Livewire\Attributes\Computed;
use Barryvdh\DomPDF\Facade\Pdf;

class ListExams extends ListRecords
{
    protected static string $resource = ExamResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('export')
                ->label(__('Export'))
                ->color('gray')
                ->icon('heroicon-o-document-arrow-down')
                ->visible(fn () => in_array(panel()->getId(), ['admin', 'student', 'guardian']))
                ->form([
                    Forms\Components\Select::make('group_id')
                        ->label(trans_choice('Group|Groups', 1))
                        ->options($this->groups->pluck('name', 'id'))
                        ->live()
                        ->required(),
                    Forms\Components\Select::make('student_id')
                        ->label(trans_choice('Student|Students', 1))
                        ->visible(in_array(panel()->getId(), ['admin', 'teacher']))
                        ->options(function (Get $get) {
                            if ($group = $this->groups->firstWhere('id', $get('group_id'))) {
                                return $group->students->mapWithKeys(fn ($student) => [$student->id => $student->name]);
                            }
                            return [];
                        })
                        ->required(),
                ])
                ->action(function (array $data) {
                    $group = $this->groups->firstWhere('id', $data['group_id']);
                    $student = match (panel()->getId()) {
                        default => $group->students->firstWhere('id', $data['student_id']),
                        'student' => panel()->auth()->user(),
                        'guardian' => tenant(),
                    };
                    $pdf = Pdf::loadView('pdf.exams', [
                        'school' => config('app.name'),
                        'group' => $group,
                        'student' => $student,
                    ])->stream();
                    return response()->streamDownload(fn () => print($pdf), 'exams.pdf');
                })
                ->modalSubmitActionLabel(__('Export'))
        ];
    }

    #[Computed]
    public function groups()
    {
        return GroupResource::getEloquentQuery()->with([
            'season:id,name',
            'students:id,name',
            'sessions' => fn ($query) => $query->select(['id', 'group_id', 'subject_id'])->has('exam')->with([
                'subject:id,name',
                'exam' => ['students:id,name'],
            ]),
        ])->latest()->get();
    }
}
