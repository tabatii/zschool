<div x-data="{
    primary: 'bg-custom-50 text-custom-600 ring-custom-600/10 dark:bg-custom-400/10 dark:text-custom-400 dark:ring-custom-400/30',
    gray: 'bg-gray-50 text-gray-600 ring-gray-600/10 dark:bg-gray-400/10 dark:text-gray-400 dark:ring-gray-400/20',
}">
    <x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
        <div class="w-full">
            <div class="flex flex-wrap gap-y-4 -mx-2">
                @foreach($getState() ?: [] as $i => $student)
                    <div class="w-full sm:w-6/12 xl:w-4/12 px-2">
                        <div class="ring-1 ring-gray-200 dark:ring-gray-800 rounded-xl p-2">
                            <div class="flex flex-wrap mb-2">
                                <div class="shrink-0">
                                    @php($avatar = asset("storage/{$student['data']['avatar']}"))
                                    <x-filament::avatar class="rounded-full" size="h-[4rem] w-[4rem]" src="{{ $avatar }}" />
                                </div>
                                <div class="w-[calc(100%-4rem)] ps-2 py-1">
                                    <p class="text-sm truncate mb-3">{{ data_get($student, 'data.name') }}</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach(\App\Enums\Presence::cases() as $case)
                                            <x-filament::badge
                                                size="sm"
                                                class="cursor-pointer"
                                                x-bind:class="{
                                                    [primary]: $wire.$get('{{$getStatePath()}}.{{$i}}.status') === '{{ $case->value }}',
                                                    [gray]: $wire.$get('{{$getStatePath()}}.{{$i}}.status') !== '{{ $case->value }}',
                                                }"
                                                x-bind:style="{
                                                    '--c-50': 'var(--primary-50)',
                                                    '--c-400': 'var(--primary-400)',
                                                    '--c-600': 'var(--primary-600)',
                                                }"
                                                x-on:click="$wire.$set('{{$getStatePath()}}.{{$i}}.status', '{{ $case->value }}', false)"
                                            >
                                                {{ $case->getLabel() }}
                                            </x-filament::badge>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div>
                                <x-filament::input.wrapper>
                                    <x-filament::input
                                        type="text"
                                        wire:model="{{$getStatePath()}}.{{$i}}.absence_reason"
                                        placeholder="{{ __('Absence reason') }}"
                                    />
                                </x-filament::input.wrapper>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </x-dynamic-component>
</div>