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
            <div class="overflow-x-auto py-2">
                <table class="w-full">
                    <thead>
                        <tr>
                            <th colspan="2"></th>
                            @foreach($this->dates as $date)
                                <th class="p-2">
                                    <div class="flex justify-center">
                                        <div @class([
                                            'text-primary-600' => $date->isToday(),
                                            'text-danger-600' => $date->isSunday() && !$date->isToday(),
                                        ])>
                                            <p class="font-medium">{{ str($date->shortDayName)->ucfirst()->remove('.') }}</p>
                                            <p class="text-5xl font-light">{{ $date->format('d') }}</p>
                                        </div>
                                    </div>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($this->group?->students ?: [] as $student)
                            <tr>
                                <td class="border border-gray-200 px-2 py-1">{{ $student->name }}</td>
                                <td class="border border-gray-200 px-2 py-1">
                                    <span>{{ __('Total') }}: </span>
                                    <span class="text-danger-600 font-medium">{{ $student->absence_sessions_count }}</span>
                                </td>
                                @foreach($this->dates as $date)
                                    <td class="border border-gray-200 px-2 py-1">
                                        <div class="flex gap-1">
                                            @foreach($this->group->sessions->where(fn ($session) => $session->starts_at->isSameDay($date)) as $session)
                                                @php($pivot = $session->students->firstWhere('id', $student->id)?->pivot)
                                                <div style="{{ $session->width_styles }}" @class([
                                                    'group relative rounded-full cursor-pointer h-3',
                                                    'bg-gray-200' => $pivot?->status === null,
                                                    'bg-success-400' => $pivot?->status->isPresent(),
                                                    'bg-warning-300' => $pivot?->status->isLate(),
                                                    'bg-orange-400' => $pivot?->status->isAJ($pivot?->is_justified),
                                                    'bg-danger-600' => $pivot?->status->isAbsent($pivot?->is_justified),
                                                ]) wire:click="mountAction('open', @js(['session_id' => $session->id, 'student_id' => $student->id]))">
                                                    <div class="tooltip start-[calc(50%-3rem)] w-[6rem]">
                                                        <span>{{ $session->starts_at_date?->format('H:i') }}</span>
                                                        <span>-</span>
                                                        <span>{{ $session->ends_at_date?->format('H:i') }}</span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                @endforeach
                            </tr>
                        @empty
                            <tr>
                                <td class="border border-gray-200 text-center px-2 py-8" colspan="{{ $this->dates->count() + 2 }}">
                                    <div class="flex justify-center mb-2">
                                        <div class="bg-gray-100 rounded-full p-2">
                                            <x-heroicon-o-x-mark class="h-8 w-8" />
                                        </div>
                                    </div>
                                    <p>{{ __('filament-tables::table.empty.heading', ['model' => strtolower(trans_choice('Student|Students', 10))]) }}</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @isset($model)
                <div class="flex flex-wrap gap-4">
                    <div class="flex items-center gap-1">
                        <div class="rounded-full bg-success-400 h-4 w-4"></div>
                        <p>{{ __('Present') }}</p>
                    </div>
                    <div class="flex items-center gap-1">
                        <div class="rounded-full bg-warning-300 h-4 w-4"></div>
                        <p>{{ __('Late') }}</p>
                    </div>
                    <div class="flex items-center gap-1">
                        <div class="rounded-full bg-orange-400 h-4 w-4"></div>
                        <p>{{ __('Justified absent') }}</p>
                    </div>
                    <div class="flex items-center gap-1">
                        <div class="rounded-full bg-danger-600 h-4 w-4"></div>
                        <p>{{ __('Absent') }}</p>
                    </div>
                </div>
            @endisset
            <div class="absolute -inset-2 sm:-inset-6 bg-white/75 rounded-b-xl z-10" wire:loading></div>
            <x-filament-actions::modals />
        </div>        
    </x-filament::section>
</x-filament-widgets::widget>
