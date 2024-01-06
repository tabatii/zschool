<div x-data="{ state: $wire.$entangle('{{ $getStatePath() }}') }">
    <x-dynamic-component :component="$getFieldWrapperView()" :field="$field">
        <div class="w-full">
            <div class="flex justify-between text-xs px-1.5 mb-1">
                @foreach($options as $text => $value)
                    <div class="relative inline-flex text-center pt-4">
                        <div class="hidden sm:block absolute top-0 -start-4 -end-4 font-medium" x-bind:class="{'!block': state == {{ $value }}}">
                            <span class="cursor-pointer" x-on:click="state = {{ $value }}">{{ $text }}</span>
                        </div>
                        <div class="w-1">|</div>
                    </div>
                @endforeach
            </div>
            <div class="relative flex">
                <div class="absolute inset-0 bottom-0 flex pointer-events-none px-2">
                    <div class="absolute top-0 start-0 bottom-0 bg-primary-600 rounded-full w-4"></div>
                    <div class="bg-primary-600 rounded-full" x-bind:style="{width: ((state - 60) / 3) + '%'}"></div>
                </div>
                <input
                    type="range"
                    class="range-field appearance-none bg-gray-100 dark:bg-gray-800 text-primary-600 rounded-full h-2 w-full"
                    x-model.number="state"
                    step="30"
                    min="60"
                    max="360"
                />
            </div>
        </div>
    </x-dynamic-component>
</div>