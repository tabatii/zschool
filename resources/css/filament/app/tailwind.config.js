import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

export default {
    presets: [preset],
    content: [
        './app/Livewire/**/*.php',
        './app/Filament/App/**/*.php',
        './resources/views/livewire/**/*.blade.php',
        './resources/views/filament/app/**/*.blade.php',
        './resources/views/components/app/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
}
