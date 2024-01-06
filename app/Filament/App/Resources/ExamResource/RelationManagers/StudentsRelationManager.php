<?php

namespace App\Filament\App\Resources\ExamResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentsRelationManager extends RelationManager
{
    protected static string $relationship = 'students';

    protected static bool $isLazy = false;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Full name'))
                    ->limit(50)
                    ->sortable(),
                Tables\Columns\TextInputColumn::make('result')
                    ->label(__('Result'))
                    ->rules(['nullable', 'numeric']),
                Tables\Columns\TextInputColumn::make('note')
                    ->label(__('Note'))
                    ->rules(['nullable']),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Tables\Actions\AttachAction::make()
                //     ->form(fn (Tables\Actions\AttachAction $action): array => [
                //         $action->getRecordSelect(),
                //         Forms\Components\TextInput::make('result')
                //             ->label(__('Result'))
                //             ->numeric()
                //             ->nullable(),
                //         Forms\Components\Textarea::make('note')
                //             ->label(__('Note'))
                //             ->rows(3)
                //             ->nullable(),
                //     ])
                //     ->preloadRecordSelect()
                //     ->recordSelectOptionsQuery(function (Builder $query) {
                //         return $query; // get only group students
                //     })
            ])
            ->actions([
                // Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DetachBulkAction::make(),
                // ]),
            ])
            ->emptyStateActions([
                //
            ])
            ->paginated(false)
            ->defaultSort('name')
            ->heading(trans_choice('Student|Students', 10))
            ->modelLabel(trans_choice('Student|Students', 1))
            ->pluralModelLabel(trans_choice('Student|Students', 10));
    }
}
