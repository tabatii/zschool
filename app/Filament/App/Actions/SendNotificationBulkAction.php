<?php

namespace App\Filament\App\Actions;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\BulkAction;
use Filament\Forms;

class SendNotificationBulkAction extends BulkAction
{
    public static function getDefaultName(): ?string
    {
        return 'sendNotificationBulk';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->visible(fn () => in_array(panel()->getId(), ['admin']));

        $this->color('info');

        $this->label(__('Message'));

        $this->icon('heroicon-o-chat-bubble-bottom-center-text');

        $this->form([
            Forms\Components\Textarea::make('content')
                ->label(__('Content'))
                ->rows(3)
                ->autosize()
                ->required(),
        ]);

        $this->action(function (array $data, Collection $records) {
            $records->filter(fn (Model $record) => !$record->is(panel()->auth()->user()))->each(function (Model $record) use ($data) {
                Notification::make()
                    ->title(panel()->auth()->user()->name)
                    ->body($data['content'])
                    ->info()
                    ->sendToDatabase($record);
            });
            Notification::make()
                ->title(__('Sent'))
                ->success()
                ->send();
        });
    }
}
