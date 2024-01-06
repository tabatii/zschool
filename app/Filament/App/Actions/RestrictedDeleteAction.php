<?php

namespace App\Filament\App\Actions;

use Filament\Tables\Actions\DeleteAction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;

class RestrictedDeleteAction extends DeleteAction
{
    public static function getDefaultName(): ?string
    {
        return 'restrictedDelete';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->using(function (Model $record) {
            try {
                return $record->delete();
            } catch (QueryException $e) {
                if ($e->errorInfo[1] === 1451) {
                    $this->failureNotificationTitle(__('You cannot delete this record'));
                    return false;
                }
                return throw $e;
            }
        });
    }
}
