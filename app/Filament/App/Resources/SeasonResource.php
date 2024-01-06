<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\SeasonResource\Pages;
use App\Filament\App\Resources\SeasonResource\RelationManagers;
use App\Models\Season;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Carbon\Carbon;

class SeasonResource extends Resource
{
    protected static ?string $model = Season::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 5;

    public static function getModelLabel(): string
    {
        return trans_choice('Season|Seasons', 1);
    }

    public static function getPluralModelLabel(): string
    {
        return trans_choice('Season|Seasons', 10);
    }

    public static function getNavigationGroup(): string
    {
        return __('Management');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('Name'))
                    ->columnSpan('full')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('starts_at')
                    ->label(__('Season starts at'))
                    ->required()
                    ->dehydrateStateUsing(fn (string $state): Carbon => Carbon::parse($state)->startOfDay()),
                Forms\Components\DatePicker::make('ends_at')
                    ->label(__('Season ends at'))
                    ->required()
                    ->dehydrateStateUsing(fn (string $state): Carbon => Carbon::parse($state)->endOfDay()),
                Forms\Components\DatePicker::make('ramadan_starts_at')
                    ->label(__('Ramadan starts at'))
                    ->nullable()
                    ->dehydrateStateUsing(fn (?string $state): ?Carbon => filled($state) ? Carbon::parse($state)->startOfDay() : null),
                Forms\Components\DatePicker::make('ramadan_ends_at')
                    ->label(__('Ramadan ends at'))
                    ->nullable()
                    ->dehydrateStateUsing(fn (?string $state): ?Carbon => filled($state) ? Carbon::parse($state)->endOfDay() : null),
                Forms\Components\Textarea::make('description')
                    ->label(__('Description'))
                    ->columnSpan('full')
                    ->rows(3)
                    ->nullable(),
                Forms\Components\Toggle::make('is_active')
                    ->label(__('Is active'))
                    ->columnSpan('full')
                    ->default(false)
                    ->required(),
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
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('starts_at')
                    ->label(__('Season starts at'))
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('ends_at')
                    ->label(__('Season ends at'))
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('ramadan_starts_at')
                    ->label(__('Ramadan starts at'))
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('ramadan_ends_at')
                    ->label(__('Ramadan ends at'))
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('Is active'))
                    ->boolean()
                    ->toggleable(),
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
            ->defaultSort('id', 'desc');
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSeasons::route('/'),
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
