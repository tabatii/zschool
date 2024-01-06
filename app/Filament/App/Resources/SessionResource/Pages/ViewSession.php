<?php

namespace App\Filament\App\Resources\SessionResource\Pages;

use App\Filament\App\Resources\SessionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSession extends ViewRecord
{
    protected static string $resource = SessionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
