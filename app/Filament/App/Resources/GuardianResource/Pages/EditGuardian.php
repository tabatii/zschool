<?php

namespace App\Filament\App\Resources\GuardianResource\Pages;

use App\Filament\App\Resources\GuardianResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditGuardian extends EditRecord
{
    protected static string $resource = GuardianResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            //
        ];
    }
}
