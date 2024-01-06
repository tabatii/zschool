<?php

namespace App\Filament\App\Actions;

use Illuminate\Database\Eloquent\Model;
use Filament\Notifications\Notification;
use Filament\Tables\Actions\Action;
use Filament\Forms;

class SendNotificationAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'sendNotification';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->visible(fn (Model $record) => in_array(panel()->getId(), ['admin']) && !$record->is(panel()->auth()->user()));

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

        $this->action(function (array $data, Model $record) {
            Notification::make()
                ->title(panel()->auth()->user()->name)
                ->body($data['content'])
                ->info()
                ->sendToDatabase($record);
            Notification::make()
                ->title(__('Sent'))
                ->success()
                ->send();
        });
    }
}
