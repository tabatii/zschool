<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\BranchResource\Pages;
use App\Filament\App\Resources\BranchResource\RelationManagers;
use App\Models\Branch;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BranchResource extends Resource
{
    protected static ?string $model = Branch::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 8;

    public static function getModelLabel(): string
    {
        return trans_choice('Branch|Branches', 1);
    }

    public static function getPluralModelLabel(): string
    {
        return trans_choice('Branch|Branches', 10);
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
                    Forms\Components\TextInput::make('shortcut')
                        ->label(__('Short name'))
                        ->required()
                        ->maxLength(255)
                        ->unique(ignoreRecord: true),
                    Forms\Components\Textarea::make('description')
                        ->label(__('Description'))
                        ->columnSpan('full')
                        ->rows(3)
                        ->nullable(),
                ])
                ->columns(2),
                Forms\Components\Repeater::make('branchSubjects')->schema([
                    Forms\Components\Select::make('subject_id')
                        ->label(trans_choice('Subject|Subjects', 1))
                        ->options(\App\Models\Subject::pluck('name', 'id'))
                        ->required(),
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
                        ->maxValue(10),
                ])
                ->label(trans_choice('Subject|Subjects', 10))
                ->columnSpan('full')
                ->columns(4)
                ->defaultItems(1)
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
                Tables\Columns\TextColumn::make('shortcut')
                    ->label(__('Short name'))
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
            RelationManagers\SubjectsRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBranches::route('/'),
            'create' => Pages\CreateBranch::route('/create'),
            'edit' => Pages\EditBranch::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return match (panel()->getId()) {
            default => parent::getEloquentQuery(),
            'teacher' => parent::getEloquentQuery()->whereHas('groups', function (Builder $q) {
                return $q->whereRelation('teachers', 'id', panel()->auth()->id());
            }),
            'student' => parent::getEloquentQuery()->whereHas('groups', function (Builder $q) {
                return $q->whereRelation('students', 'id', panel()->auth()->id());
            }),
            'guardian' => parent::getEloquentQuery()->whereHas('groups', function (Builder $q) {
                return $q->whereRelation('students', 'id', tenant()->getKey());
            }),
        };
    }
}
