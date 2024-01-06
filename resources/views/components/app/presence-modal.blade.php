<div class="cursor-auto">
    <div class="mb-6">
        <p class="mb-1">{{ $student->name }}</p>
        @isset ($pivot)
            <div class="flex flex-wrap gap-2">
                <div class="flex items-center gap-1">
                    <div style="{{ $session->width_styles }}" @class([
                        'rounded-full h-3',
                        'bg-success-400' => $pivot->status->isPresent(),
                        'bg-warning-300' => $pivot->status->isLate(),
                        'bg-orange-400' => $pivot->status->isAJ($pivot->is_justified),
                        'bg-danger-600' => $pivot->status->isAbsent($pivot->is_justified),
                    ])></div>
                    <span class="text-sm font-semibold">{{ $pivot->status->isAJ($pivot->is_justified) ? __('Justified absent') : $pivot->status->getLabel() }}</span>
                </div>
                @if ($pivot->status->isAJ($pivot->is_justified))
                    <div @class([
                        'flex items-center justify-center text-xs font-medium tracking-tighter rounded-md ring-1 ring-inset',
                        'bg-gray-50 text-gray-600 ring-gray-600/10 dark:bg-gray-400/10 dark:text-gray-400 dark:ring-gray-400/20 min-w-[1rem] p-1',
                    ])>
                        <span>{{ $pivot->absence_reason }}</span>
                    </div>
                @endif
            </div>
        @endisset
    </div>
    <div class="relative bg-primary-400 text-white border-t-4 border-primary-500 rounded-b-xl cursor-auto h-full px-6 py-2 -mx-6 -mb-6">
        <div class="mb-2">
            <p class="text-2xl font-bold">
                <span style="font-family: 'Handlee', cursive">{{ $session->subject->name }}</span>
            </p>
            <p class="text-sm">
                <span>{{ $session->teacher->name }}</span>
            </p>
            <p class="text-sm">
                <span>{{ $group->name }}</span>
                @isset($session->room)
                    <span>({{ $session->room?->name }})</span>
                @endisset
            </p>
            <p class="text-sm">
                <span>{{ $session->starts_at_date?->format('H:i') }}</span>
                <span>-</span>
                <span>{{ $session->ends_at_date?->format('H:i') }}</span>
            </p>
        </div>
        <div class="flex justify-end gap-2 text-sm">
            @if(panel()->auth()->user()->can('update', $session))
                <a href="{{ filament_route('App\\Resources\\SessionResource@edit', ['record' => $session->id]) }}" class="hover:underline">
                    <span>{{ __('Presence') }}</span>
                </a>
            @endif
            @if(panel()->auth()->user()->can('view', $session))
                <a href="{{ filament_route('App\\Resources\\SessionResource@view', ['record' => $session->id]) }}" class="hover:underline">
                    <span>{{ __('filament-actions::view.single.label') }}</span>
                </a>
            @endif
        </div>
    </div>
</div>
