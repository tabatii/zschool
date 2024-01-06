<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\SubjectResource\Pages;
use App\Filament\App\Resources\SubjectResource\RelationManagers;
use App\Models\Subject;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\FontWeight;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SubjectResource extends Resource
{
    protected static ?string $model = Subject::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 6;

    public static function getModelLabel(): string
    {
        return trans_choice('Subject|Subjects', 1);
    }

    public static function getPluralModelLabel(): string
    {
        return trans_choice('Subject|Subjects', 10);
    }

    public static function getNavigationGroup(): string
    {
        return __('Management');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\TextInput::make('name')
                        ->label(__('Name'))
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Textarea::make('description')
                        ->label(__('Description'))
                        ->columnSpan('full')
                        ->rows(3)
                        ->nullable(),
                ])
                ->columns(1),
                Forms\Components\Repeater::make('topics')->schema([
                    Forms\Components\TextInput::make('name')
                        ->label(__('Name'))
                        ->required()
                        ->maxLength(255),
                    Forms\Components\Textarea::make('description')
                        ->label(__('Description'))
                        ->rows(2)
                        ->nullable(),
                ])
                ->label(trans_choice('Topic|Topics', 10))
                ->columnSpan('full')
                ->columns(1)
                ->grid(2)
                ->defaultItems(2)
                ->relationship()
                ->visible(fn (string $operation) => $operation === 'create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->limit(50)
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                \App\Filament\App\Actions\RestrictedDeleteAction::make(),
            ])
            ->bulkActions([
                //
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->defaultSort('id', 'desc')
            ->recordUrl(null);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\TopicsRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubjects::route('/'),
            'create' => Pages\CreateSubject::route('/create'),
            'edit' => Pages\EditSubject::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return match (panel()->getId()) {
            default => parent::getEloquentQuery(),
            'teacher' => parent::getEloquentQuery()->whereRelation('teachers', 'id', panel()->auth()->id()),
            'student' => parent::getEloquentQuery()->whereHas('branches', function (Builder $q) {
                return $q->whereHas('groups', fn (Builder $q2) => $q2->whereRelation('students', 'id', panel()->auth()->id()));
            }),
            'guardian' => parent::getEloquentQuery()->whereHas('branches', function (Builder $q) {
                return $q->whereHas('groups', fn (Builder $q2) => $q2->whereRelation('students', 'id', tenant()->getKey()));
            }),
        };
    }
}
