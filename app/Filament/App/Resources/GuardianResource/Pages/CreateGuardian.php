<?php

namespace App\Filament\App\Resources\GuardianResource\Pages;

use App\Filament\App\Resources\GuardianResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateGuardian extends CreateRecord
{
    protected static string $resource = GuardianResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
