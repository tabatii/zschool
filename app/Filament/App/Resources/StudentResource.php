<?php

namespace App\Filament\App\Resources;

use App\Filament\App\Resources\StudentResource\Pages;
use App\Filament\App\Resources\StudentResource\RelationManagers;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Filters\SelectFilter;
use Phpsa\FilamentPasswordReveal\Password;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-s-user-group';

    protected static ?int $navigationSort = 3;

    public static function getModelLabel(): string
    {
        return trans_choice('Student|Students', 1);
    }

    public static function getPluralModelLabel(): string
    {
        return trans_choice('Student|Students', 10);
    }

    public static function getNavigationGroup(): string
    {
        return trans_choice('User|Users', 10);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()->schema([
                    Forms\Components\Toggle::make('is_active')
                        ->label(__('Is active'))
                        ->columnSpan(['default' => 6])
                        ->default(true)
                        ->required(),
                    Forms\Components\FileUpload::make('avatar')
                        ->label(__('Photo'))
                        ->columnSpan(['default' => 6, 'xl' => 1])
                        ->directory(now()->format('d-m-Y'))
                        ->disk('public')
                        ->moveFiles()
                        ->avatar()
                        ->nullable()
                        ->maxSize(2048),
                    Forms\Components\Group::make([
                        Forms\Components\Select::make('gender')
                            ->label(__('Gender'))
                            ->columnSpan(['default' => 6, 'lg' => 2])
                            ->options(\App\Enums\Gender::class)
                            ->required(),
                        Forms\Components\TextInput::make('name')
                            ->label(__('Full name'))
                            ->columnSpan(['default' => 6, 'lg' => 2])
                            ->required()
                            ->maxLength(255),
                        Forms\Components\TextInput::make('username')
                            ->label(__('Username'))
                            ->columnSpan(['default' => 6, 'lg' => 2])
                            ->required()
                            ->maxLength(255),
                        Password::make('password')
                            ->label(__('Password'))
                            ->columnSpan(['default' => 6, 'lg' => 3])
                            ->generatable(true)
                            ->passwordUsesSymbols(false)
                            ->dehydrated(fn ($state) => filled($state))
                            ->required(fn (string $context): bool => $context === 'create')
                            ->minLength(8)
                            ->confirmed(),
                        Password::make('password_confirmation')
                            ->label(__('Confirm password'))
                            ->columnSpan(['default' => 6, 'lg' => 3])
                            ->required(fn (string $context): bool => $context === 'create')
                            ->dehydrated(false),
                    ])
                    ->columns(6)
                    ->columnSpan(['default' => 6, 'xl' => 5]),
                    Forms\Components\Group::make([
                        Forms\Components\TextInput::make('mobile')
                            ->label(__('Phone number'))
                            ->columnSpan(['default' => 6, 'lg' => 2])
                            ->nullable()
                            ->tel()
                            ->unique(ignoreRecord: true),
                        Forms\Components\TextInput::make('email')
                            ->label(__('Email'))
                            ->columnSpan(['default' => 6, 'lg' => 2])
                            ->nullable()
                            ->email()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true),
                        Forms\Components\DatePicker::make('birthday')
                            ->label(__('Birthday'))
                            ->columnSpan(['default' => 6, 'lg' => 2])
                            ->nullable(),
                        Forms\Components\Textarea::make('address')
                            ->label(__('Address'))
                            ->columnSpan(['default' => 6])
                            ->rows(3)
                            ->nullable(),
                        Forms\Components\FileUpload::make('attachments')
                            ->label(__('Attachments'))
                            ->columnSpan(['default' => 6])
                            ->directory(now()->format('d-m-Y'))
                            ->disk('private')
                            ->moveFiles()
                            ->multiple()
                            ->maxSize(10240)
                            ->maxFiles(5),
                    ])
                    ->columns(6)
                    ->columnSpan(['default' => 6]),
                ])
                ->columns(6),
                Forms\Components\KeyValue::make('details')
                    ->label(__('Other details'))
                    ->columnSpan('full'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label(__('Photo'))
                    ->circular()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Full name'))
                    ->limit(50)
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('username')
                    ->label(__('Username'))
                    ->limit(50)
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->visible(panel()->auth()->user()->can('create', Student::class)),
                Tables\Columns\TextColumn::make('mobile')
                    ->label(__('Phone number'))
                    ->limit(20)
                    ->searchable()
                    ->toggleable()
                    ->visible(panel()->auth()->user()->can('create', Student::class)),
                Tables\Columns\TextColumn::make('email')
                    ->label(__('Email'))
                    ->limit(50)
                    ->searchable()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('birthday')
                    ->label(__('Birthday'))
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable()
                    ->visible(panel()->auth()->user()->can('create', Student::class)),
                Tables\Columns\IconColumn::make('is_active')
                    ->label(__('Is active'))
                    ->boolean()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('group')
                    ->label(trans_choice('Group|Groups', 1))
                    ->relationship('groups', 'name')
                    ->visible(fn () => in_array(panel()->getId(), ['admin'])),
            ])
            ->actions([
                \App\Filament\App\Actions\SendNotificationAction::make(),
                Tables\Actions\EditAction::make(),
                \App\Filament\App\Actions\RestrictedDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    \App\Filament\App\Actions\SendNotificationBulkAction::make(),
                ]),
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
            //
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
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
                return $q->whereIn('id', panel()->auth()->user()->groups()->pluck('id'));
            })->where('id', '<>', panel()->auth()->id()),
            'guardian' => parent::getEloquentQuery()->whereHas('groups', function (Builder $q) {
                return $q->whereIn('id', tenant()->groups()->pluck('id'));
            })->where('id', '<>', tenant()->getKey()),
        };
    }
}
