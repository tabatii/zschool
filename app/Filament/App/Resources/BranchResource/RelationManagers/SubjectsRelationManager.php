<?php

namespace App\Filament\App\Resources\BranchResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Grouping\Group;

class SubjectsRelationManager extends RelationManager
{
    protected static string $relationship = 'subjects';

    protected static bool $isLazy = false;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('minutes')
                    ->label(__('Total hours'))
                    ->sortable()
                    ->formatStateUsing(fn (int $state): int => $state / 60),
                Tables\Columns\TextColumn::make('factor')
                    ->label(__('Factor'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('year')
                    ->label(__('Year')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        $action->getRecordSelect(),
                        Forms\Components\TextInput::make('minutes')
                            ->label(__('Total hours'))
                            ->numeric()
                            ->default(0)
                            ->required()
                            ->maxValue(9000)
                            ->formatStateUsing(fn (int $state): int => $state / 60)
                            ->dehydrateStateUsing(fn (int $state): int => $state * 60),
                        Forms\Components\TextInput::make('factor')
                            ->label(__('Factor'))
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->minValue(1)
                            ->maxValue(10),
                        Forms\Components\TextInput::make('year')
                            ->label(__('Year'))
                            ->numeric()
                            ->default(1)
                            ->required()
                            ->minValue(1)
                            ->maxValue(5),
                    ])
                    ->preloadRecordSelect()
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                //
            ])
            ->allowDuplicates()
            ->paginated(false)
            ->heading(trans_choice('Subject|Subjects', 10))
            ->modelLabel(trans_choice('Subject|Subjects', 1))
            ->pluralModelLabel(trans_choice('Subject|Subjects', 10))
            ->defaultGroup('year')
            ->groups([
                Group::make('year')
                    ->label(__('Year'))
                    ->collapsible(),
            ]);
    }
}
