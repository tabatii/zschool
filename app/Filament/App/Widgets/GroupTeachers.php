<?php

namespace App\Filament\App\Widgets;

use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Group;

class GroupTeachers extends BaseWidget
{
    protected static bool $isDiscovered = false;

    protected static bool $isLazy = false;

    public Group $record;

    public function table(Table $table): Table
    {
        return $table
            ->heading(__('Group teachers'))
            ->query(fn () => $this->record->teachers()->latest('id'))
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label(__('Photo'))
                    ->circular(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('Full name'))
                    ->limit(50)
                    ->sortable(),
            ])
            ->defaultPaginationPageOption(5)
            ->queryStringIdentifier('teachers'); // not working
    }

    public function getListeners()
    {
        return [
            'calendar-updated' => '$refresh',
        ];
    }
}
