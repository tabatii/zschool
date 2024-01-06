<x-filament-widgets::widget>
    @if($groups->count())
        <div class="flex justify-center">
            <x-filament::tabs class="flex-wrap justify-center gap-y-1" x-data>
                @forelse ($groups as $group)
                    <x-filament::tabs.item :active="$active == $group->id" x-on:click="$dispatch('group-changed', { group_id: {{ $group->id }} })">
                        {{ $group->name }}
                    </x-filament::tabs.item>
                @empty
                    <x-filament::tabs.item class="pointer-events-none" tag="p">
                        {{ __('filament-tables::table.empty.heading', ['model' => strtolower(trans_choice('Group|Groups', 10))]) }}
                    </x-filament::tabs.item>
                @endforelse
            </x-filament::tabs>
        </div>
    @endif
</x-filament-widgets::widget>
