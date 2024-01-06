<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\GroupResource\Pages;
use App\Filament\App\Resources\GroupResource\RelationManagers;
use App\Models\Group;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GroupResource extends Resource
{
    protected static ?string $model = Group::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 9;

    public static function getModelLabel(): string
    {
        return trans_choice('Group|Groups', 1);
    }

    public static function getPluralModelLabel(): string
    {
        return trans_choice('Group|Groups', 10);
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
                    Forms\Components\TextInput::make('year')
                        ->label(__('Year'))
                        ->numeric()
                        ->default(1)
                        ->required()
                        ->minValue(1)
                        ->maxValue(5),
                    Forms\Components\Select::make('season_id')
                        ->label(trans_choice('Season|Seasons', 1))
                        ->relationship(name: 'season', titleAttribute: 'name')
                        ->required(),
                    Forms\Components\Select::make('branch_id')
                        ->label(trans_choice('Branch|Branches', 1))
                        ->relationship(name: 'branch', titleAttribute: 'name')
                        ->required(),
                    Forms\Components\Textarea::make('description')
                        ->label(__('Description'))
                        ->columnSpan('full')
                        ->rows(3)
                        ->nullable(),
                ])
                ->columnSpan(3)
                ->columns(2),
                Forms\Components\Section::make()->schema([
                    Forms\Components\Group::make()->schema([
                        Forms\Components\CheckboxList::make('students')
                            ->label(trans_choice('Student|Students', 10))
                            ->relationship(name: 'students', titleAttribute: 'name', modifyQueryUsing: fn (Builder $query) => $query->active())
                            ->searchable(),
                    ])
                    ->extraAttributes([
                        'class' => 'overflow-auto px-6 py-4 -mx-6 -my-4',
                        'style' => 'max-height:332px',
                    ])
                ])
                ->columnSpan(1)
            ])
            ->columns(4);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->limit(50)
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('year')
                    ->label(__('Year'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('season.name')
                    ->label(trans_choice('Season|Seasons', 1))
                    ->limit(50)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('branch.name')
                    ->label(trans_choice('Branch|Branches', 1))
                    ->limit(50)
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make([
                    Infolists\Components\TextEntry::make('name')->label(__('Name')),
                    Infolists\Components\TextEntry::make('year')->label(__('Year')),
                    Infolists\Components\TextEntry::make('season.name')->label(trans_choice('Season|Seasons', 1)),
                    Infolists\Components\TextEntry::make('branch.name')->label(trans_choice('Branch|Branches', 1)),
                    Infolists\Components\TextEntry::make('description')->label(__('Description'))->columnSpan('full'),
                ])
                ->columns(4)
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGroups::route('/'),
            'create' => Pages\CreateGroup::route('/create'),
            'view' => Pages\ViewGroup::route('/{record}'),
            'edit' => Pages\EditGroup::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return match (panel()->getId()) {
            default => parent::getEloquentQuery(),
            'teacher' => parent::getEloquentQuery()->whereRelation('teachers', 'id', panel()->auth()->id()),
            'student' => parent::getEloquentQuery()->whereRelation('students', 'id', panel()->auth()->id()),
            'guardian' => parent::getEloquentQuery()->whereRelation('students', 'id', tenant()->getKey()),
        };
    }
}
