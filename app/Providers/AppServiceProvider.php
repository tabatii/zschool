<?php

namespace App\Providers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentView;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Resources\Resource;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->setUpFilament();
        $this->setUpModel();
        $this->setUpUrl();
    }

    protected function setUpFilament(): void
    {
        Resource::scopeToTenant(false);
        FilamentAsset::register([
            Css::make('handlee-font', 'https://fonts.googleapis.com/css2?family=Handlee&display=swap'),
            Js::make('moment-js', 'https://momentjs.com/downloads/moment.js'),
        ]);
        FilamentView::registerRenderHook(
            'panels::auth.login.form.after',
            fn (): View => view('components.home-link'),
        );
    }

    protected function setUpModel(): void
    {
        Model::shouldBeStrict(!$this->app->isProduction());
        Model::unguard();
    }

    protected function setUpUrl(): void
    {
        if($this->app->isProduction()) {
            URL::forceScheme('https');
        }
    }
}
