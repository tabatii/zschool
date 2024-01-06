<form class="space-y-4" wire:submit="submit">
    <div>
        <input
            type="text"
            class="border-slate-200 focus:border-orange-500 focus:ring-orange-500 rounded w-full"
            placeholder="Name"
            wire:model="name"
        />
        @error('name')
            <span class="text-red-400 text-sm">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <input
            type="text"
            class="border-slate-200 focus:border-orange-500 focus:ring-orange-500 rounded w-full"
            placeholder="Email"
            wire:model="email"
        />
        @error('email')
            <span class="text-red-400 text-sm">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <input
            type="text"
            class="border-slate-200 focus:border-orange-500 focus:ring-orange-500 rounded w-full"
            placeholder="Object"
            wire:model="object"
        />
        @error('object')
            <span class="text-red-400 text-sm">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <textarea
            type="text"
            class="border-slate-200 focus:border-orange-500 focus:ring-orange-500 rounded w-full"
            placeholder="Message"
            wire:model="message"
            rows="6"
        ></textarea>
        @error('message')
            <span class="text-red-400 text-sm">{{ $message }}</span>
        @enderror
    </div>
    <div>
        <button type="submit" wire:loading.attr="disabled" @class([
            'inline-flex justify-center bg-orange-500 hover:bg-orange-300 text-white font-medium',
            'rounded transition-colors duration-500 w-full px-4 py-2',
        ])>
            <span>Send</span>
        </button>
    </div>
</form>