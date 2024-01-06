<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\SessionResource\Pages;
use App\Filament\App\Resources\SessionResource\RelationManagers;
use App\Models\Session;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SessionResource extends Resource
{
    protected static ?string $model = Session::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?int $navigationSort = 10;

    public static function getModelLabel(): string
    {
        return trans_choice('Session|Sessions', 1);
    }

    public static function getPluralModelLabel(): string
    {
        return trans_choice('Session|Sessions', 10);
    }

    public static function getNavigationLabel(): string
    {
        return __('Presence');
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
                    Forms\Components\Toggle::make('is_ignored')
                        ->label(__('Ignore when paying'))
                        ->inline(false)
                        ->default(false),
                    Forms\Components\Select::make('topics')
                        ->label(trans_choice('Topic|Topics', 10))
                        ->relationship(
                            name: 'topics',
                            titleAttribute: 'name',
                            modifyQueryUsing: function (Builder $query, Session $record) {
                                return $query->where('subject_id', $record->subject_id)->oldest('id');
                            }
                        )
                        ->multiple()
                        ->preload()
                        ->required(),
                    Forms\Components\Textarea::make('description')
                        ->label(__('Description'))
                        ->rows(3)
                        ->nullable(),
                    Forms\Components\ViewField::make('students')
                        ->view('filament.app.forms.components.presence-field')
                        ->label(__('Presence'))
                        ->required(),
                ])
                ->columns(1),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                //
            ])
            ->bulkActions([
                //
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make()->schema([
                    Infolists\Components\TextEntry::make('topics.name')
                        ->label(trans_choice('Topic|Topics', 10))
                        ->listWithLineBreaks()
                        ->bulleted(),
                    Infolists\Components\TextEntry::make('description')
                        ->label(__('Description')),
                ])
                ->columns(1),
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
            'index' => Pages\Presence::route('/'),
            'view' => Pages\ViewSession::route('/{record}'),
            'edit' => Pages\EditSession::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return match (panel()->getId()) {
            default => parent::getEloquentQuery(),
            'teacher' => parent::getEloquentQuery()->whereRelation('teacher', 'id', panel()->auth()->id()),
            'student' => parent::getEloquentQuery()->whereHas('group', function (Builder $q) {
                return $q->whereRelation('students', 'id', panel()->auth()->id());
            }),
            'guardian' => parent::getEloquentQuery()->whereHas('group', function (Builder $q) {
                return $q->whereRelation('students', 'id', tenant()->getKey());
            }),
        };
    }
}
