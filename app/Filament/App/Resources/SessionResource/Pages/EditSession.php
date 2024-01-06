<?php

namespace App\Filament\App\Resources\SessionResource\Pages;

use App\Filament\App\Resources\SessionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Presence;

class EditSession extends EditRecord
{
    protected static string $resource = SessionResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $this->record->load($this->record->is_attended ? ['group', 'students'] : ['group.students']);
        $data['students'] = $this->record->is_attended
            ? $this->record->students->sortBy('name')->map(fn ($s) => [
                'student_id' => $s->id,
                'status' => $s->pivot->status->value,
                'absence_reason' => $s->pivot->absence_reason,
                'data' => $s->only('name', 'avatar'),
            ])
            : $this->record->group->students->sortBy('name')->map(fn ($s) => [
                'student_id' => $s->id,
                'status' => Presence::PRESENT->value,
                'data' => $s->only('name', 'avatar'),
            ]);
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['is_attended'] = true;
        return $data;
    }

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        $record->update(collect($data)->except('students')->all());
        $record->students()->sync(collect($data['students'])->map(function ($s) {
            return [
                'student_id' => $s['student_id'],
                'status' => $s['status'],
                'absence_reason' => ($is_absent = $s['status'] === Presence::ABSENT->value) ? $s['absence_reason'] : null,
                'is_justified' => $is_absent && filled($s['absence_reason']) ? true : false,
            ];
        }));
        return $record;
    }
}
