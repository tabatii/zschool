<div tabindex="0" class="calendar-session overflow-hidden bg-white rounded-lg cursor-default z-[3] focus:z-[4] min-w-[7rem] w-full">
    <div @class([
        'relative bg-primary-400 text-white h-full px-2',
        'fading' => now()->between($session->starts_at_date, $session->ends_at_date),
    ])>
        <div class="flex flex-col justify-between overflow-auto h-full gap-4 py-2 pe-2 -me-2">
            <div>
                <div class="flex justify-between">
                    <p class="overflow-hidden text-ellipsis text-xl font-bold">
                        <span style="font-family: 'Handlee', cursive">{{ $session->subject->name }}</span>
                    </p>
                    @if(count($actions))
                        <div class="pt-1">
                            <x-filament-actions::group
                                :actions="$actions"
                                icon="heroicon-m-ellipsis-vertical"
                                dropdown-placement="bottom-end"
                                color="current"
                            />
                        </div>
                    @endif
                </div>
                <p class="overflow-hidden text-ellipsis text-xs">
                    <span>{{ $session->teacher->name }}</span>
                </p>
                <p class="overflow-hidden text-ellipsis text-xs">
                    <span>{{ $session->group->name }}</span>
                    @isset($session->room)
                        <span>({{ $session->room?->name }})</span>
                    @endisset
                </p>
                <p class="overflow-hidden text-ellipsis text-xs">
                    <span>{{ $session->starts_at_date?->format('H:i') }}</span>
                    <span>-</span>
                    <span>{{ $session->ends_at_date?->format('H:i') }}</span>
                </p>
            </div>
            <div class="flex justify-end gap-2 text-xs">
                @if($canUpdate)
                    <a href="{{ filament_route('App\\Resources\\SessionResource@edit', ['record' => $session->id]) }}" class="hover:underline">
                        <span>{{ __('Presence') }}</span>
                    </a>
                @endif
                @if($canView)
                    <a href="{{ filament_route('App\\Resources\\SessionResource@view', ['record' => $session->id]) }}" class="hover:underline">
                        <span>{{ __('filament-actions::view.single.label') }}</span>
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>