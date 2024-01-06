<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\ExamResource\Pages;
use App\Filament\App\Resources\ExamResource\RelationManagers;
use App\Models\Exam;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ExamResource extends Resource
{
    protected static ?string $model = Exam::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 11;

    public static function getModelLabel(): string
    {
        return trans_choice('Exam|Exams', 1);
    }

    public static function getPluralModelLabel(): string
    {
        return trans_choice('Exam|Exams', 10);
    }

    public static function getNavigationGroup(): string
    {
        return trans_choice('Group|Groups', 1);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\Select::make('topics')
                        ->label(trans_choice('Topic|Topics', 10))
                        ->relationship(
                            name: 'topics',
                            titleAttribute: 'name',
                            modifyQueryUsing: function (Builder $query, Exam $record) {
                                return $query->whereHas('subject', fn ($q) => $q->whereRelation('sessions', 'id', $record->session_id))->oldest('id');
                            }
                        )
                        ->multiple()
                        ->preload()
                        ->required(),
                    Forms\Components\Textarea::make('description')
                        ->label(__('Description'))
                        ->rows(3)
                        ->nullable(),
                ])
                ->columns(1)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('session.group.name')
                    ->label(trans_choice('Group|Groups', 1))
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('session.teacher.name')
                    ->label(trans_choice('Teacher|Teachers', 1))
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('session.subject.name')
                    ->label(trans_choice('Subject|Subjects', 1))
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('session.room.name')
                    ->label(trans_choice('Room|Rooms', 1))
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('session.starts_at')
                    ->label(__('Session start time'))
                    ->date('d/m/Y - (H:i)')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('session.ends_at')
                    ->label(__('Session end time'))
                    ->date('d/m/Y - (H:i)')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('session.starts_at_ramadan')
                    ->label(__('Session start time (Ramadan)'))
                    ->date('d/m/Y - (H:i)')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('session.ends_at_ramadan')
                    ->label(__('Session end time (Ramadan)'))
                    ->date('d/m/Y - (H:i)')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('exam_result')
                    ->label(__('Result')),
            ])
            ->filters([
                Tables\Filters\Filter::make('is_active')
                    ->label(__('Only active groups'))
                    ->toggle()
                    ->default()
                    ->query(function (Builder $query): Builder {
                        return $query->whereHas('session', function (Builder $q1) {
                            return $q1->whereHas('group', fn (Builder $q2) => $q2->whereHas('season', fn (Builder $q3) => $q3->active()));
                        });
                    })
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                \App\Filament\App\Actions\RestrictedDeleteAction::make(),
            ])
            ->bulkActions([
                //
            ])
            ->modifyQueryUsing(fn (Builder $query) => match (panel()->getId()) {
                default => $query,
                'student' => $query->addSelect([
                    'exam_result' => DB::table('exam_student')
                        ->select('result')
                        ->whereColumn('exam_id', 'exams.id')
                        ->where('student_id', panel()->auth()->id())->limit(1)
                ]),
                'guardian' => $query->addSelect([
                    'exam_result' => DB::table('exam_student')
                        ->select('result')
                        ->whereColumn('exam_id', 'exams.id')
                        ->where('student_id', tenant()->getKey())->limit(1)
                ])
            })
            ->defaultSort('id', 'desc')
            ->recordUrl(null);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\StudentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExams::route('/'),
            'edit' => Pages\EditExam::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return match (panel()->getId()) {
            default => parent::getEloquentQuery(),
            'teacher' => parent::getEloquentQuery()->whereHas('session', fn ($query) => $query->whereRelation('teacher', 'id', panel()->auth()->id())),
            'student' => parent::getEloquentQuery()->whereHas('session', function (Builder $query) {
                return $query->whereHas('group', fn ($q) => $q->whereRelation('students', 'id', panel()->auth()->id()));
            }),
            'guardian' => parent::getEloquentQuery()->whereHas('session', function (Builder $query) {
                return $query->whereHas('group', fn ($q) => $q->whereRelation('students', 'id', tenant()->getKey()));
            }),
        };
    }
}
