<x-filament-widgets::widget>
    <x-filament::section collapsible>
        <x-slot name="heading">{{ $title }}</x-slot>
        <div class="relative -m-4 sm:m-0">
            <div class="flex flex-wrap lg:justify-between items-center gap-y-2 mb-4">
                <div class="w-4/12 order-2 lg:order-1 select-none">
                    <div class="flex gap-1 sm:gap-2">
                        <button type="button" wire:click="subWeek" class="bg-gray-100 hover:bg-gray-200 rounded-full p-2">
                            <x-heroicon-o-chevron-left @class(['h-5 w-5', 'rotate-180' => app()->getLocale() === 'ar']) />
                        </button>
                        <button type="button" wire:click="addWeek" class="bg-gray-100 hover:bg-gray-200 rounded-full p-2">
                            <x-heroicon-o-chevron-right @class(['h-5 w-5', 'rotate-180' => app()->getLocale() === 'ar']) />
                        </button>
                    </div>
                </div>
                <div class="w-full lg:w-4/12 order-1 lg:order-2">
                    <p class="text-xl text-center font-medium">
                        <span>{{ $startOfWeek->day }}</span>
                        <span>{{ str($startOfWeek->shortMonthName)->ucfirst()->remove('.') }}</span>
                        <span @class(['hidden' => $startOfWeek->year === $endOfWeek->year])>{{ $startOfWeek->year }}</span>
                        <span>-</span>
                        <span>{{ $endOfWeek->day }}</span>
                        <span>{{ str($endOfWeek->shortMonthName)->ucfirst()->remove('.') }}</span>
                        <span>{{ $endOfWeek->year }}</span>
                    </p>
                </div>
                <div class="w-8/12 lg:w-4/12 order-3 select-none">
                    <div class="flex justify-end">
                        <x-filament::input.wrapper>
                            <x-filament::input type="week" class="min-w-0" wire:model.live="week" />
                        </x-filament::input.wrapper>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto select-none">
                <table class="table w-full min-h-[30rem]">
                    <thead>
                        <tr>
                            @foreach($this->dates as $date)
                                <th class="border border-gray-200 w-[14%] p-2">
                                    <div class="flex justify-center">
                                        <div @class([
                                            'text-primary-600' => $date->isToday(),
                                            'text-danger-600' => $date->isSunday() && !$date->isToday(),
                                        ])>
                                            <p class="font-medium">{{ ucfirst($date->dayName) }}</p>
                                            <p class="text-5xl font-light">{{ $date->format('d') }}</p>
                                        </div>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @foreach($this->dates as $date)
                                <td class="relative align-top border border-gray-200 !pt-0 p-2 space-y-2">
                                    <div class="min-w-[7rem] w-full"></div>
                                    @foreach($this->sessions->where(fn ($session) => $session->starts_at->isSameDay($date)) as $session)
                                        @php($canView = panel()->auth()->user()->can('view', $session))
                                        @php($canUpdate = panel()->auth()->user()->can('update', $session))
                                        @php($canDelete = panel()->auth()->user()->can('delete', $session))
                                        <x-app.calendar-session :$session :$canView :$canUpdate :actions="array_values(array_filter([
                                            $canUpdate ? ($this->examAction)(['session_id' => $session->id, 'subject_id' => $session->subject_id]) : null,
                                            $canUpdate ? ($this->editAction)(['session_id' => $session->id]) : null,
                                            $canDelete ? ($this->deleteAction)(['session_id' => $session->id]) : null,
                                            $canDelete ? ($this->bulkDeleteAction)(['session_id' => $session->id]) : null,
                                        ]))" />
                                    @endforeach
                                </td>
                            @endforeach
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="absolute -inset-2 sm:-inset-6 bg-white/75 rounded-b-xl z-10" wire:loading></div>
        </div>        
        <x-filament-actions::modals />
    </x-filament::section>
</x-filament-widgets::widget>
